<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'account_number',
        'account_name',
        'status',
        'notes',
    ];

    /**
     * Active scope filter
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Search scope filter
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('type', 'like', "%{$search}%")
              ->orWhere('account_number', 'like', "%{$search}%")
              ->orWhere('account_name', 'like', "%{$search}%");
        });
    }

    /**
     * Type Badge Helper
     */
    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'bank' => 'bg-blue-100 text-blue-800 border-blue-200',
            'cash' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'digital' => 'bg-purple-100 text-purple-800 border-purple-200',
            'credit' => 'bg-amber-100 text-amber-800 border-amber-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }

    /**
     * Status Badge Helper
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'inactive' => 'bg-slate-100 text-slate-700 border-slate-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
