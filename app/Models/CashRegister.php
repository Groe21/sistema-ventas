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
        'counted_card_amount',
        'counted_transfer_amount',
        'difference',
        'cash_breakdown',
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
        'counted_card_amount' => 'decimal:2',
        'counted_transfer_amount' => 'decimal:2',
        'difference' => 'decimal:2',
        'cash_breakdown' => 'array',
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
    public function getTotalIncome(?string $paymentMethod = null): float
    {
        $query = $this->cashMovements()->where('type', 'income');
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }
        return (float) $query->sum('amount');
    }

    /**
     * Calculate total expenses.
     */
    public function getTotalExpenses(?string $paymentMethod = null): float
    {
        $query = $this->cashMovements()->where('type', 'expense');
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }
        return (float) $query->sum('amount');
    }

    /**
     * Calculate expected amount.
     */
    public function calculateExpectedAmount(): float
    {
        // El cierre de caja se cuadra contra efectivo fisico.
        return (float) $this->opening_amount + $this->getTotalIncome('cash') - $this->getTotalExpenses('cash');
    }

    public function calculateExpectedByMethod(): array
    {
        $cash = $this->calculateExpectedAmount();
        $card = $this->getTotalIncome('card') - $this->getTotalExpenses('card');
        $transfer = $this->getTotalIncome('transfer') - $this->getTotalExpenses('transfer');

        return [
            'cash' => round((float) $cash, 2),
            'card' => round((float) $card, 2),
            'transfer' => round((float) $transfer, 2),
            'total' => round((float) ($cash + $card + $transfer), 2),
        ];
    }
}
