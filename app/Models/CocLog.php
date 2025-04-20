<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CocLog extends Model
{
    protected $fillable = [
        'user_id',
        'created_by',
        'activity_name',
        'activity_date',
        'coc_earned',
        'consumed',
        'issuance',
        'certification_coc',
        'expires_at',
        'is_expired'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_expired' => 'boolean',
        'consumed' => 'boolean',
    ];

    protected $appends = ['status'];

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
            $model->expires_at = $model->expires_at ?? 
                now()->timezone('Asia/Manila')->startOfDay()->addYear();
        });
    }

    public function checkExpiration()
    {
        if (!$this->is_expired && !$this->consumed && $this->expires_at->isPast()) {
            DB::transaction(function () {
                $this->user->decrement('overtime_balance', $this->coc_earned);
                $this->is_expired = true;
                $this->save();
            });
        }
    }

    public function getStatusAttribute()
    {
        if ($this->consumed) {
            return 'used';
        }
        if ($this->is_expired) {
            return 'expired';
        }
        return 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('is_expired', false)
                    ->where('consumed', false)
                    ->where('expires_at', '>', now());
    }

    public function scopeUsable($query)
    {
        return $query->where('is_expired', false)
                    ->where('consumed', false);
    }

    public function markAsUsed()
    {
        if ($this->is_expired || $this->consumed) {
            throw new \Exception('Cannot use an expired or already consumed COC log');
        }

        DB::transaction(function () {
            $this->consumed = true;
            $this->save();
        });
    }
}