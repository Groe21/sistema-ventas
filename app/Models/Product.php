<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id',
        'code',
        'name',
        'description',
        'category',
        'brand',
        'cost_price',
        'sale_price',
        'has_iva',
        'has_ice',
        'stock',
        'min_stock',
        'stock_type',
        'image',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'has_iva' => 'boolean',
        'has_ice' => 'boolean',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the business that owns the product.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the sale items for the product.
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Check if product is in stock.
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Check if product needs restocking.
     */
    public function needsRestocking(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    /**
     * Get price with IVA.
     */
    public function getPriceWithIva(): float
    {
        if (!$this->has_iva) {
            return $this->sale_price;
        }

        return $this->sale_price * 1.12; // 12% IVA in Ecuador
    }

    /**
     * Reduce stock.
     */
    public function reduceStock(int $quantity): void
    {
        $this->stock -= $quantity;
        $this->save();
    }

    /**
     * Increase stock.
     */
    public function increaseStock(int $quantity): void
    {
        $this->stock += $quantity;
        $this->save();
    }
}
