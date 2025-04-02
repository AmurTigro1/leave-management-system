<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveFile extends Model
{
    protected $fillable = ['leave_id', 'file_path', 'file_name'];

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }
}
