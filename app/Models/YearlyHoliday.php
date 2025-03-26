<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YearlyHoliday extends Model
{
    protected $fillable = ['name', 'date', 'type', 'repeats_annually'];
    
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->where(function($q) use ($startDate, $endDate) {
            // Exact date matches
            $q->whereBetween('date', [$startDate, $endDate]);
            
            // Annual repeating holidays
            $q->orWhere(function($q2) use ($startDate, $endDate) {
                $q2->where('repeats_annually', true)
                   ->whereRaw("MONTH(date) BETWEEN ? AND ?", 
                       [date('m', strtotime($startDate)), date('m', strtotime($endDate))])
                   ->whereRaw("DAY(date) BETWEEN ? AND ?", 
                       [date('d', strtotime($startDate)), date('d', strtotime($endDate))]);
            });
        });
    }
    

    protected $casts = [
        'date' => 'date',
        'repeats_annually' => 'boolean',
    ];
}
