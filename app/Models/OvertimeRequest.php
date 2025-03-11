<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date_filed',
        'position',
        'office_division',
        'working_hours_applied',
        'inclusive_date_start',
        'inclusive_date_end',
        'approved_days',
        'disapproval_reason',
        'earned_hours',
        'hr_officer_id',
        'supervisor_id',
        'supervisor_status',
        'hr_status',
        'status',
    ];
    
    /**
     * Get the user who filed the overtime request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the supervisor who reviewed the request.
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Get the HR officer who reviewed the request.
     */
    public function hrOfficer()
    {
        return $this->belongsTo(User::class, 'hr_officer_id');
    }

    /**
     * Check if the request is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the request is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if the request is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Get the formatted date filed.
     */
    public function getFormattedDateFiledAttribute()
    {
        return \Carbon\Carbon::parse($this->date_filed)->format('F d, Y');
    }
}
