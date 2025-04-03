<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UpdateLeaveBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:update-balance';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increase vacation and sick leave balance by 2.5 days every month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
               // ✅ Update all users' leave balances
               $users = User::all();

               foreach ($users as $user) {
                   $user->vacation_leave_balance += 1.5;
                   $user->sick_leave_balance += 1.5;
                   $user->save();
               }
       
               // ✅ Log the result
               Log::info('Leave balances updated by 1.5 days for all users.');
       
               $this->info('Leave balances successfully updated.');
           
    }
}
