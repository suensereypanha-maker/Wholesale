<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'sku',
        'product_name',
        'image',
        'short_description',
        'description',
        'details',
        'category',
        'quantity',
        'reserved_quantity',
        'min_reorder_level',
        'max_capacity',
        'unit_cost',
        'retail_price',
        'tier_prices',
        'rack_location',
        'status',
        'notes',
    ];

    protected $casts = [
        'details' => 'array',
        'tier_prices' => 'array',
        'unit_cost' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'min_reorder_level' => 'integer',
        'max_capacity' => 'integer',
    ];

    /**
     * Warehouse where stock is located.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get available quantity (Quantity - Reserved).
     */
    public function getAvailableQuantityAttribute(): int
    {
        return max(0, $this->quantity - $this->reserved_quantity);
    }

    /**
     * Get Product Image URL or Default Component Icon.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            return asset('storage/' . $this->image);
        }

        // Return curated high quality hardware component fallback image placeholder
        return 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=300&auto=format&fit=crop&q=80';
    }

    /**
     * Get total valuation of this stock item line.
     */
    public function getTotalValueAttribute(): float
    {
        return (float) ($this->quantity * $this->unit_cost);
    }

    /**
     * Dynamic Stock Status Badge Label.
     */
    public function getComputedStatusAttribute(): string
    {
        if ($this->quantity <= 0) {
            return 'out_of_stock';
        }
        if ($this->quantity <= $this->min_reorder_level) {
            return 'low_stock';
        }
        if ($this->max_capacity > 0 && $this->quantity >= $this->max_capacity) {
            return 'overstocked';
        }
        return 'in_stock';
    }

    /**
     * Scope low stock items.
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'min_reorder_level')->where('quantity', '>', 0);
    }

    /**
     * Scope out of stock items.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    /**
     * Scope in stock items.
     */
    public function scopeInStock($query)
    {
        return $query->whereColumn('quantity', '>', 'min_reorder_level');
    }

    /**
     * Scope overstocked items.
     */
    public function scopeOverstocked($query)
    {
        return $query->whereColumn('quantity', '>=', 'max_capacity')->where('max_capacity', '>', 0);
    }

    /**
     * Get profit margin per unit ($).
     */
    public function getProfitMarginAttribute(): float
    {
        return (float) ($this->retail_price - $this->unit_cost);
    }

    /**
     * Get profit margin percentage (%).
     */
    public function getProfitMarginPercentageAttribute(): float
    {
        if ($this->unit_cost > 0) {
            return round((($this->retail_price - $this->unit_cost) / $this->unit_cost) * 100, 1);
        }
        return 0.0;
    }

    /**
     * Get applicable unit price for a given order quantity based on tier prices.
     */
    public function getPriceForQuantity(int $quantity): float
    {
        if (!empty($this->tier_prices) && is_array($this->tier_prices)) {
            foreach ($this->tier_prices as $tier) {
                $min = (int) ($tier['min_qty'] ?? 1);
                $max = isset($tier['max_qty']) && $tier['max_qty'] !== null && $tier['max_qty'] !== '' ? (int) $tier['max_qty'] : null;
                $price = (float) ($tier['price'] ?? $this->retail_price);

                if ($quantity >= $min && ($max === null || $quantity <= $max)) {
                    return $price;
                }
            }
        }

        return (float) $this->retail_price;
    }
}
