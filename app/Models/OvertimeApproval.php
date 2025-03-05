<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeApproval extends Model
{
    /** @use HasFactory<\Database\Factories\OvertimeApprovalFactory> */
    use HasFactory;

    protected $fillable = ['overtime_request_id', 'status', 'approved_days', 'disapproval_reason'];

    public function overtimeRequest()
    {
        return $this->belongsTo(OvertimeRequest::class);
    }
}
