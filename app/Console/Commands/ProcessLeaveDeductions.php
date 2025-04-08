<?php

namespace App\Console\Commands;

use App\Models\LeaveLog;
use Illuminate\Console\Command;
use App\Models\TimeManagement;
use App\Models\MonthlySummary;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class ProcessLeaveDeductions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:deduct';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deduct leave based on total late and undertime per user monthly.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastMonth = now()->subMonth();
        $monthLabel = $lastMonth->format('F Y');
    
        Log::info("🔄 Starting leave deduction for {$monthLabel}");
    
        $records = TimeManagement::whereMonth('date', $lastMonth->month)
            ->whereYear('date', $lastMonth->year)
            ->get();
    
        $monthlyRecords = $records->groupBy('user_id');
    
        foreach ($monthlyRecords as $userId => $userRecords) {
            $absences = $userRecords->whereNull('check_in')->count();
            $totalLateMinutes = $userRecords->sum('total_late_absences');
            $leaveDeduction = round($totalLateMinutes / 480, 2); // 8 hrs = 480 mins
    
            // Save summary
            MonthlySummary::updateOrCreate(
                ['month' => $monthLabel, 'user_id' => $userId],
                [
                    'total_absences' => $absences,
                    'total_late_minutes' => $totalLateMinutes,
                    'leave_deduction' => $leaveDeduction
                ]
            );
    
            $user = User::find($userId);
    
            if ($user) {
                Log::info("🎯 User ID {$userId}: Late = {$totalLateMinutes} min → Deduction = {$leaveDeduction}");
    
                if ($leaveDeduction > 0) {
                    $oldBalance = $user->vacation_leave_balance;
                    $user->vacation_leave_balance = max(0, $user->vacation_leave_balance - $leaveDeduction);
                    $user->save();
    
                    LeaveLog::create([
                        'user_id' => $user->id,
                        'change_amount' => -$leaveDeduction,
                        'new_balance' => $user->vacation_leave_balance,
                        'reason' => "Monthly late/undertime deduction for {$monthLabel}",
                        'effective_date' => now()->toDateString(),
                    ]);
    
                    Log::info("✅ User ID {$userId}: Leave balance updated from {$oldBalance} to {$user->vacation_leave_balance}");
                } else {
                    Log::info("⏩ User ID {$userId}: No deduction needed.");
                }
            } else {
                Log::warning("⚠️ User ID {$userId} not found.");
            }
        }
    
        Log::info("✅ Leave deductions have been processed for {$monthLabel}.");
        return Command::SUCCESS;
    }
    
}
