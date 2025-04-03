<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeManagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'break_out',
        'break_in',
        'check_out',
        'total_hours',
        'total_late_absences',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
