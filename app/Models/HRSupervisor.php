<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRSupervisor extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisor_name', 
        'hr_name',
        'supervisor_signature',
        'hr_signature'
     ];
}
