<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetLeaveBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:reset-balances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command resets some of the leave balances of all the users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach($users as $user){
            $user->wellness_leave_balance = 5;
            $user->save();
        }

         Log::info('Leave balances updated to there initial values.');

        $this->info('Leave balances successfully updated.');
    }
}
