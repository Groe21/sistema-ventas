<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id',
        'name',
        'email',
        'phone',
        'identification_type',
        'identification',
        'address',
        'city',
        'province',
        'credit_limit',
        'credit_days',
        'notes',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'credit_limit' => 'decimal:2',
        'credit_days' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the business that owns the customer.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the sales for the customer.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Check if customer is final consumer.
     */
    public function isFinalConsumer(): bool
    {
        return $this->identification_type === 'consumidor_final';
    }

    /**
     * Get total sales amount.
     */
    public function getTotalSales(): float
    {
        return $this->sales()
            ->where('status', 'completed')
            ->sum('total');
    }

    /**
     * Get pending credit amount.
     */
    public function getPendingCredit(): float
    {
        return $this->sales()
            ->where('payment_status', '!=', 'paid')
            ->where('status', 'completed')
            ->sum('total');
    }

    public function points()
    {
        return $this->hasOne(CustomerPoint::class);
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function getPointsBalance(): int
    {
        return $this->points?->points_balance ?? 0;
    }
}
