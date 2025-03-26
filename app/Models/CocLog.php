<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CocLog extends Model
{
    protected $fillable = [
        'user_id',
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
}
