<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'ruc',
        'commercial_name',
        'email',
        'phone',
        'address',
        'city',
        'province',
        'accountant_name',
        'legal_representative',
        'special_taxpayer',
        'required_accounting',
        'logo',
        'status',
        'subscription_start',
        'subscription_end',
        'plan',
        'onboarding_completed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'special_taxpayer' => 'boolean',
        'required_accounting' => 'boolean',
        'subscription_start' => 'date',
        'subscription_end' => 'date',
        'onboarding_completed' => 'boolean',
    ];

    /**
     * Get the users for the business.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the products for the business.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the customers for the business.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get the sales for the business.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the cash registers for the business.
     */
    public function cashRegisters(): HasMany
    {
        return $this->hasMany(CashRegister::class);
    }

    /**
     * Get the cash movements for the business.
     */
    public function cashMovements(): HasMany
    {
        return $this->hasMany(CashMovement::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->whereIn('status', ['active', 'trial'])
            ->where('ends_at', '>=', now())
            ->latest();
    }

    public function currentPlan(): ?Plan
    {
        $sub = $this->activeSubscription;
        return $sub?->plan;
    }

    public function customerPoints(): HasMany
    {
        return $this->hasMany(CustomerPoint::class);
    }

    /**
     * Check if business is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if subscription is valid.
     */
    public function hasActiveSubscription(): bool
    {
        if (!$this->subscription_end) {
            return false;
        }

        return $this->subscription_end >= now();
    }
}
