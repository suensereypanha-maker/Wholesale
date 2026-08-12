<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_number',
        'user_id',
        'customer_id',
        'stock_id',
        'company_name',
        'contact_name',
        'email',
        'phone',
        'product_name',
        'quantity',
        'target_price',
        'offered_price',
        'required_date',
        'status',
        'message',
        'admin_notes',
        'quoted_at',
    ];

    protected $casts = [
        'target_price' => 'decimal:2',
        'offered_price' => 'decimal:2',
        'required_date' => 'date',
        'quoted_at' => 'datetime',
    ];

    /**
     * Relationship to User (if registered).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to Customer profile.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relationship to Stock product item.
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Helper to generate unique quote numbers (QT-2026-XXXX).
     */
    public static function generateUniqueCode(): string
    {
        $year = date('Y');
        $maxId = (static::max('id') ?? 0) + 1;
        $code = 'QT-' . $year . '-' . str_pad($maxId + 1000, 4, '0', STR_PAD_LEFT);

        while (static::where('quote_number', $code)->exists()) {
            $maxId++;
            $code = 'QT-' . $year . '-' . str_pad($maxId + 1000, 4, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    /**
     * Dynamic CSS Badge for Quote Status.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
            'under_review' => 'bg-blue-50 text-blue-700 border-blue-200',
            'quoted' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'converted' => 'bg-purple-50 text-purple-700 border-purple-200',
            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };
    }

    /**
     * Formatted Status Label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Review',
            'under_review' => 'Under Review',
            'quoted' => 'Quote Offered',
            'approved' => 'Approved by Buyer',
            'converted' => 'Converted to Order',
            'rejected' => 'Rejected',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
