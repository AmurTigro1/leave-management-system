<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CocLog extends Model
{
    protected $fillable = [
        'user_id',
        'created_by',
        'activity_name',
        'activity_date',
        'coc_earned',
        'issuance',
    ];

    protected $dates = ['activity_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
