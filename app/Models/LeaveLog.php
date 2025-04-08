<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveLog extends Model
{
    protected $fillable = ['user_id', 'change_amount', 'effective_date',  'new_balance', 'reason'];

}

