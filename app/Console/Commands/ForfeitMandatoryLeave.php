<?php

namespace App\Console\Commands;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
            // foreach (User::all() as $user) {
            //     $mandatoryUsed = Leave::where('user_id', $user->id)
            //         ->where('leave_type', 'Mandatory Leave')
            //         ->whereYear('start_date', now()->year)
            //         ->where('status', 'approved')
            //         ->sum('days_applied');

            //     $toForfeit = max(0, 5 - $mandatoryUsed);

            //     if ($toForfeit > 0) {
            //         $user->vacation_leave_balance -= $toForfeit;
            //         $user->save();
            //     }
            // }

        $users = User::all();

        foreach ($users as $user) {
            // Total used leave this year (only approved vacation leaves)
            $usedMandatoryLeave = DB::table('leaves')
                ->where('user_id', $user->id)
                ->where('leave_type', 'Mandatory Leave')
                ->where('status', 'approved')
                ->whereYear('start_date', now()->year)
                ->sum(DB::raw('DATEDIFF(end_date, start_date) + 1'));

            $mandatoryRequired = $user->mandatory_leave_balance;

            if ($usedMandatoryLeave < $mandatoryRequired) {
                // Forfeit the unused portion
                $remainingMandatory = $mandatoryRequired - $usedMandatoryLeave;

                // Optionally: track forfeited days if needed
                // $user->forfeited_days += $remainingMandatory;

                // Reset or flag the mandatory leave (depends on your app logic)
                $user->mandatory_leave_balance = 0;
                $user->tmp_manado;
                $user->save();

                // Log or notify
                logger("User {$user->id} forfeited {$remainingMandatory} mandatory leave day(s).");
            }
        }

    }

}