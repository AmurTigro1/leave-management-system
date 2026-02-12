<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Define your scheduled tasks here
        // $schedule->command('leave:update-balance')
        //          ->monthlyOn(1, '00:00'); // Runs at midnight on the 1st of every month
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        // Manually register the command if needed
        // $this->app->singleton('command.leave.update-balances', function () {
        //     return new \App\Console\Commands\UpdateLeaveBalance;
        // });

        // $this->commands([
        //     'command.leave.update-balance',
        // ]);

        require base_path('routes/console.php');
    }
}