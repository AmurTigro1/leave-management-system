<?php
// app/Services/HolidayService.php
namespace App\Services;

use App\Models\YearlyHoliday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class YearlyHolidayService
{
    public function getHolidaysBetweenDates(Carbon $startDate, Carbon $endDate)
    {
        $cacheKey = "holidays_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}";
        return Cache::remember($cacheKey, now()->addWeeks(1), function() use ($startDate, $endDate) {
            $holidays = YearlyHoliday::betweenDates($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))->get();
            
            $holidayDates = [];
        
            foreach ($holidays as $holiday) {
                if ($holiday->repeats_annually) {
                    $years = range($startDate->year, $endDate->year);
                    foreach ($years as $year) {
                        $date = Carbon::create($year, $holiday->date->month, $holiday->date->day);
                        if ($date->between($startDate, $endDate)) {
                            $holidayDates[] = $date->format('Y-m-d');
                        }
                    }
                } else {
                    $holidayDates[] = $holiday->date->format('Y-m-d');
                }
            }
        
            return array_unique($holidayDates);
        });
        
    }

    public function isHoliday(Carbon $date)
{
    return YearlyHoliday::whereDate('date', $date)->exists() || 
           YearlyHoliday::where('repeats_annually', true)
                ->whereMonth('date', $date->month)
                ->whereDay('date', $date->day)
                ->exists();
}

}