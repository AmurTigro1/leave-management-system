<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::macro('addBusinessDays', function ($days, $holidays = []) {
            $date = $this->copy();
            while ($days > 0) {
                $date->addDay();
                if (!$date->isWeekend() && !in_array($date->format('Y-m-d'), $holidays)) {
                    $days--;
                }
            }
            return $date;
        });
    }
}
