<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'product_limit',
        'user_limit',
        'features',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'product_limit' => 'integer',
        'user_limit' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->features ?? []);
    }

    public function hasUnlimitedProducts(): bool
    {
        return $this->product_limit === 0;
    }

    public function hasUnlimitedUsers(): bool
    {
        return $this->user_limit === 0;
    }
}
