<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = ['user_id', 'start_date', 'end_date', 'reason', 'leave_type', 'leave_details', 'abroad_details', 'status', ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
