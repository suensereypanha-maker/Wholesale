<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard & Analytics
            'view_dashboard'   => 'Can access administrative dashboard',
            'reports.view'     => 'Can view sales, revenue, customer, and quote reports',
            'view_reports'     => 'Legacy full report access',

            // B2B Orders
            'orders.view'      => 'Can view B2B order records',
            'orders.create'    => 'Can create new B2B customer orders',
            'orders.edit'      => 'Can edit order details and update order status',
            'orders.delete'    => 'Can delete or cancel order records',
            'manage_orders'    => 'Legacy full order management access',

            // Quotes & Inquiries
            'quotes.view'      => 'Can view RFQ submissions and price inquiries',
            'quotes.create'    => 'Can submit new price quotes',
            'quotes.edit'      => 'Can edit, respond to, and approve quotes',
            'quotes.delete'    => 'Can reject or delete quote requests',

            // Products Catalog
            'products.view'    => 'Can view catalog products, pricing tiers, and categories',
            'products.create'  => 'Can add new catalog items and product categories',
            'products.edit'    => 'Can edit product specs, prices, and categories',
            'products.delete'  => 'Can delete products or categories',
            'manage_products'  => 'Legacy full product catalog access',

            // Inventory & Stock
            'inventory.view'   => 'Can view warehouse stock levels and stock history',
            'inventory.create' => 'Can record Stock In and Stock Out movements',
            'inventory.edit'   => 'Can perform manual inventory stock adjustments',
            'inventory.delete' => 'Can remove inventory movement records',

            // B2B Customers & Registrations
            'customers.view'   => 'Can view B2B customer accounts and registration requests',
            'customers.create' => 'Can create new B2B customer accounts',
            'customers.edit'   => 'Can edit customer details and approve applications',
            'customers.delete' => 'Can delete or suspend customer accounts',

            // System Users Management
            'users.view'       => 'Can view administrative user list',
            'users.create'     => 'Can create new admin/staff user accounts',
            'users.edit'       => 'Can edit admin user profiles and status',
            'users.delete'     => 'Can delete admin user accounts',
            'manage_users'     => 'Legacy full user management access',

            // Roles & Permissions Security
            'roles.view'       => 'Can view security roles and permission settings',
            'roles.create'     => 'Can create new security roles',
            'roles.edit'       => 'Can update role permissions and security levels',
            'roles.delete'     => 'Can delete custom security roles',
            'manage_roles'     => 'Legacy full role management access',
        ];

        foreach ($permissions as $name => $description) {
            Permission::updateOrCreate(
                ['name' => $name],
                ['guard_name' => 'web', 'description' => $description]
            );
        }
    }
}
