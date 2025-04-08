<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySummary extends Model
{
    use HasFactory;

    protected $fillable = ['month', 'total_absences', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
