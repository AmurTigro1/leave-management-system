<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_expired' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->expires_at = now()->addYear();
        });

        static::retrieved(function ($model) {
            if (!$model->is_expired && $model->expires_at->isPast()) {
                DB::transaction(function () use ($model) {
                    $model->user->decrement('overtime_balance', $model->coc_earned);
                    $model->update(['is_expired' => true]);
                });
            }
        });
    }

    public function getIsActiveAttribute()
    {
        if (!$this->is_expired && $this->expires_at->isPast()) {
            DB::transaction(function () {
                $this->user->decrement('overtime_balance', $this->coc_earned);
                $this->is_expired = true;
                $this->save();
            });
        }
        
        return !$this->is_expired;
    }
}
