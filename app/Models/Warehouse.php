<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'location',
        'contact_name',
        'contact_email',
        'contact_phone',
        'capacity',
        'status',
        'notes',
    ];

    /**
     * Get the stocks stored in this warehouse.
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Scope active warehouses.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get total quantity of items currently stored.
     */
    public function getTotalQuantityAttribute(): int
    {
        return (int) $this->stocks()->sum('quantity');
    }

    /**
     * Get total valuation of stock stored in warehouse.
     */
    public function getTotalValuationAttribute(): float
    {
        return (float) $this->stocks->sum(function ($stock) {
            return $stock->quantity * $stock->unit_cost;
        });
    }

    /**
     * Calculate capacity usage percentage.
     */
    public function getCapacityUsagePercentAttribute(): int
    {
        if ($this->capacity <= 0) return 0;
        $totalQty = $this->total_quantity;
        return min(100, (int) round(($totalQty / $this->capacity) * 100));
    }
}
