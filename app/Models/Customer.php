<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'customer_code',
        'name',
        'company_name',
        'email',
        'phone',
        'tier',
        'wholesale_discount',
        'credit_limit',
        'total_spent',
        'total_orders',
        'payment_terms',
        'tax_id',
        'address',
        'city',
        'country',
        'status',
        'notes',
    ];

    protected $casts = [
        'wholesale_discount' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'total_spent' => 'decimal:2',
        'total_orders' => 'integer',
    ];

    /**
     * Scope a query to search customers by code, name, company, email, or phone.
     */
    public function scopeSearch($query, $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('customer_code', 'like', "%{$term}%")
              ->orWhere('name', 'like', "%{$term}%")
              ->orWhere('company_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    /**
     * Scope a query to filter by active status.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to filter by tier.
     */
    public function scopeTier($query, $tier)
    {
        if (empty($tier)) {
            return $query;
        }
        return $query->where('tier', $tier);
    }

    /**
     * Helper to get tier badge CSS classes.
     */
    public function getTierBadgeClassAttribute(): string
    {
        return match ($this->tier) {
            'VIP Platinum' => 'bg-purple-50 text-purple-700 border-purple-200',
            'Wholesale Gold' => 'bg-amber-50 text-amber-700 border-amber-200',
            'Bulk Silver' => 'bg-slate-100 text-slate-700 border-slate-300',
            default => 'bg-blue-50 text-blue-700 border-blue-200',
        };
    }

    /**
     * Helper to get status badge CSS classes.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
            'inactive' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
