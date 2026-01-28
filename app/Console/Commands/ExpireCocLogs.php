<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\CocLog;

class ExpireCocLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coc:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process expired COC logs and deduct remaining balance from users overtime';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::transaction(function () {

            // Fetch expired COC logs with their users (eager loading)
            $expiredLogs = CocLog::with('user')
                ->where('is_expired', false)
                ->whereNotNull('expires_at')
                ->where('expires_at', '<=', now())
                ->where('coc_earned', '>', 0)
                ->lockForUpdate()
                ->get();

            foreach ($expiredLogs as $cocLog) {
                $user = $cocLog->user;

                if (!$user) {
                    continue; // just in case
                }

                // Deduct remaining COC to overtime balance, prevent negatives
                $user->overtime_balance = max(0, $user->overtime_balance - $cocLog->coc_earned);

                // Mark COC log as fully consumed & expired
                $cocLog->consumed += $cocLog->coc_earned;
                $cocLog->coc_earned = 0;
                $cocLog->is_expired = true;

                $user->save();
                $cocLog->save();
            }
        });

        $this->info('Expired COC logs processed successfully.');
        return 0;
    }
}