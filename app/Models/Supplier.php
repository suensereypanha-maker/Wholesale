<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'company_name',
        'email',
        'phone',
        'website',
        'tax_id',
        'category',
        'address',
        'city',
        'country',
        'payment_terms',
        'rating',
        'status',
        'notes',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Scope filter for active suppliers
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope search query for keyword matching
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('company_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }

    /**
     * Status color utility helper
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'inactive' => 'bg-slate-100 text-slate-700 border-slate-200',
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
