<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = ['user_id', 'start_date', 'end_date', 'reason', 'leave_type', 'leave_details', 'abroad_details', 'status', 'position', 'days_applied', 'commutation', 'salary_file', 'date_filing', 'disapproval_reason',
    'approved_days_with_pay',
    'approved_days_without_pay',
    'hr_officer_id',
    'supervisor_id',
    'supervisor_status',
    'hr_status',  ];

    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
