<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'user_id',
        'order_source',
        'status',
        'payment_status',
        'payment_terms',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'shipping_address',
        'notes',
        'order_date',
        'due_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'order_date' => 'datetime',
        'due_date' => 'datetime',
    ];

    /**
     * Customer model relationship.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * User (Account) model relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order items relationship.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Status badge helper CSS classes.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'shipped' => 'bg-blue-50 text-blue-700 border-blue-200',
            'processing' => 'bg-amber-50 text-amber-700 border-amber-200',
            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }

    /**
     * Payment status badge helper CSS classes.
     */
    public function getPaymentBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'partially_paid' => 'bg-amber-50 text-amber-700 border-amber-200',
            default => 'bg-rose-50 text-rose-700 border-rose-200',
        };
    }
}
