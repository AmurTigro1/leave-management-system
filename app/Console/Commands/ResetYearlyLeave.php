<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetYearlyLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:reset-yearly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets special and solo parent leave taken at the start of a new year.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::query()->update([
            'special_leave_taken' => 0,
            'solo_parent_leave_taken' => 0,
        ]);

        $this->info('Yearly leave balances have been reset.');
    }
}
