<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'business_id',
        'plan_id',
        'status',
        'trial_ends_at',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['active', 'trial']) && $this->ends_at >= now();
    }

    public function isTrial(): bool
    {
        return $this->status === 'trial';
    }

    public function isExpired(): bool
    {
        return $this->ends_at < now();
    }

    public function daysRemaining(): int
    {
        return max(0, (int) now()->diffInDays($this->ends_at, false));
    }
}
