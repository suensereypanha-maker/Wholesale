<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    /**
     * Get module group for permission listing.
     */
    public function getModuleAttribute(): string
    {
        $name = $this->name;

        if (str_starts_with($name, 'orders.') || $name === 'manage_orders') return 'Orders & Sales';
        if (str_starts_with($name, 'quotes.')) return 'Quotes & Inquiries';
        if (str_starts_with($name, 'products.') || $name === 'manage_products') return 'Products Catalog';
        if (str_starts_with($name, 'inventory.')) return 'Inventory & Stock';
        if (str_starts_with($name, 'customers.')) return 'B2B Customers';
        if (str_starts_with($name, 'users.') || $name === 'manage_users') return 'User Management';
        if (str_starts_with($name, 'roles.') || $name === 'manage_roles') return 'Roles & Security';
        if (str_starts_with($name, 'reports.') || $name === 'view_reports' || $name === 'view_dashboard') return 'Analytics & Reports';

        return 'General System';
    }

    /**
     * Get action type (Create, Read, Update, Delete).
     */
    public function getActionAttribute(): string
    {
        $name = $this->name;

        if (str_contains($name, '.view') || str_starts_with($name, 'view_')) return 'Read (View)';
        if (str_contains($name, '.create')) return 'Create (Add)';
        if (str_contains($name, '.edit')) return 'Update (Edit)';
        if (str_contains($name, '.delete')) return 'Delete (Remove)';
        if (str_starts_with($name, 'manage_')) return 'Full Access (Legacy)';

        return 'Access';
    }

    /**
     * The roles that belong to the permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
