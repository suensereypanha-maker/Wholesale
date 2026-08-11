<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Traits\HasRoles;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'status',
    'company',
    'tax_number',
    'phone',
    'address',
    'city',
    'province',
    'zip',
    'country',
    'tier',
    'credit_limit',
    'wholesale_discount',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'company',
        'tax_number',
        'phone',
        'address',
        'city',
        'province',
        'zip',
        'country',
        'tier',
        'credit_limit',
        'wholesale_discount',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user account is approved / active.
     */
    public function isApproved(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user account is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending' || empty($this->status);
    }

    /**
     * Check if user account is rejected or suspended.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Scope a query to only include pending users.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get badge CSS styling classes for user status.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'pending'  => 'bg-amber-50 text-amber-700 border-amber-200 animate-pulse',
            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
            default    => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}

