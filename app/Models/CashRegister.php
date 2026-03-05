<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegister extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id',
        'user_id',
        'name',
        'opened_at',
        'closed_at',
        'opening_amount',
        'expected_amount',
        'actual_amount',
        'difference',
        'status',
        'opening_notes',
        'closing_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_amount' => 'decimal:2',
        'expected_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    /**
     * Get the business that owns the cash register.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the user that opened the cash register.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sales for the cash register.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the cash movements for the cash register.
     */
    public function cashMovements(): HasMany
    {
        return $this->hasMany(CashMovement::class);
    }

    /**
     * Check if cash register is open.
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if cash register is closed.
     */
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Calculate total income.
     */
    public function getTotalIncome(): float
    {
        return $this->cashMovements()
            ->where('type', 'income')
            ->sum('amount');
    }

    /**
     * Calculate total expenses.
     */
    public function getTotalExpenses(): float
    {
        return $this->cashMovements()
            ->where('type', 'expense')
            ->sum('amount');
    }

    /**
     * Calculate expected amount.
     */
    public function calculateExpectedAmount(): float
    {
        return $this->opening_amount + $this->getTotalIncome() - $this->getTotalExpenses();
    }
}
