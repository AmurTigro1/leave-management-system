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
        'working_hours_applied',
        'inclusive_date_start',
        'inclusive_date_end',
        'earned_hours',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approval()
    {
        return $this->hasOne(OvertimeApproval::class);
    }
}
