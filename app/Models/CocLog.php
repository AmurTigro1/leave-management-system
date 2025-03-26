<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CocLog extends Model
{
    protected $fillable = [
        'activity_name',
        'activity_date',
        'coc_earned',
        'issuance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
