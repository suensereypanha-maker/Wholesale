<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class SupplierPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_code',
        'supplier_id',
        'invoice_number',
        'purchase_date',
        'due_date',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_status',
        'payment_method',
        'payment_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    /**
     * Relationship with Supplier
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relationship with Creator (User)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope search filter
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('payment_code', 'like', "%{$search}%")
              ->orWhere('invoice_number', 'like', "%{$search}%")
              ->orWhereHas('supplier', function ($sq) use ($search) {
                  $sq->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Scope status filter
     */
    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if (empty($status) || $status === 'all') {
            return $query;
        }

        return $query->where('payment_status', $status);
    }

    /**
     * Status Badge Helper
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'partial' => 'bg-amber-100 text-amber-800 border-amber-300',
            'unpaid' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    /**
     * Status Label Helper
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'Total Paid',
            'partial' => 'Partially Paid',
            'unpaid' => 'Not Yet Paid',
            default => ucfirst($this->payment_status),
        };
    }
}
