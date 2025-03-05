<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompensatoryTimeLog extends Model
{
    /** @use HasFactory<\Database\Factories\CompensatoryTimeLogFactory> */
    use HasFactory;

    protected $fillable = ['employee_id', 'total_hours', 'cto_date', 'used_hours', 'remaining_hours', 'remarks'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
