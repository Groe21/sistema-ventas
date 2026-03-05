<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'product_code',
        'quantity',
        'unit_price',
        'subtotal',
        'has_iva',
        'iva_amount',
        'has_ice',
        'ice_amount',
        'discount',
        'total',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'has_iva' => 'boolean',
        'iva_amount' => 'decimal:2',
        'has_ice' => 'boolean',
        'ice_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the sale that owns the item.
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the product for the item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate subtotal.
     */
    public function calculateSubtotal(): float
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Calculate IVA amount (12% in Ecuador).
     */
    public function calculateIva(): float
    {
        if (!$this->has_iva) {
            return 0;
        }

        return $this->subtotal * 0.12;
    }

    /**
     * Calculate total.
     */
    public function calculateTotal(): float
    {
        return $this->subtotal + $this->iva_amount + $this->ice_amount - $this->discount;
    }
}
