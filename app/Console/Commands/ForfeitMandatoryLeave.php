<?php

namespace App\Console\Commands;
use App\Models\User;
use Illuminate\Console\Command;

class ForfeitMandatoryLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:forfeit-unused-mandatory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Forfeits at least 5 unused vacation leave days at the end of the year';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::where('vacation_leave_balance', '>', 0)->get();

        foreach ($users as $user) {
            $initialLeave = $user->total_annual_vacation_leave; // Example: 10 days
            $usedLeave = $initialLeave - $user->vacation_leave_balance;

            if ($usedLeave < 5) {
                // Ensure at least 5 days are used
                $leaveToForfeit = 5 - $usedLeave;
                $user->vacation_leave_balance = max(0, $user->vacation_leave_balance - $leaveToForfeit);
                $user->save();
            }
        }

        $this->info('Mandatory vacation leave rule enforced.');
    }
}
