<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveViolation extends Model
{


    protected $guarded = [];

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }

    /**
     * A violation belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}