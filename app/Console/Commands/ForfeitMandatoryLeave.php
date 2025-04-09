<?php

namespace App\Console\Commands;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ForfeitMandatoryLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forfeit-mandatory';

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
        foreach (User::all() as $user) {
            $mandatoryUsed = Leave::where('user_id', $user->id)
                ->where('leave_type', 'Mandatory Leave')
                ->whereYear('start_date', now()->year)
                ->where('status', 'approved')
                ->sum('days_applied');
        
            $toForfeit = max(0, 5 - $mandatoryUsed);
        
            if ($toForfeit > 0) {
                $user->vacation_leave_balance -= $toForfeit;
                $user->save();
            }
        }
    } 
    
}
