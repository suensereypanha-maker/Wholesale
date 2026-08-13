<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Core Security Roles
        $superAdmin = Role::updateOrCreate(
            ['name' => 'Super Admin'],
            ['guard_name' => 'web', 'description' => 'Full unrestricted system access across all modules']
        );

        $admin = Role::updateOrCreate(
            ['name' => 'Admin'],
            ['guard_name' => 'web', 'description' => 'Administrative access to manage operations, catalog, and users']
        );

        $manager = Role::updateOrCreate(
            ['name' => 'Manager'],
            ['guard_name' => 'web', 'description' => 'Managerial access for orders, stock, and product pricing']
        );

        $staff = Role::updateOrCreate(
            ['name' => 'Staff'],
            ['guard_name' => 'web', 'description' => 'Basic staff access to view orders, stock, and dashboard']
        );

        $sale = Role::updateOrCreate(
            ['name' => 'Sale'],
            ['guard_name' => 'web', 'description' => 'Sales representative access to create/edit orders, quotes, and view reports']
        );

        $userRole = Role::updateOrCreate(
            ['name' => 'User'],
            ['guard_name' => 'web', 'description' => 'Standard B2B Customer account']
        );

        // Assign Permissions
        $allPermissions = Permission::pluck('name')->toArray();

        $syncPermissions = function($roleModel, array $permissionNames) {
            $ids = Permission::whereIn('name', $permissionNames)->pluck('id');
            $roleModel->permissions()->sync($ids);
        };

        $syncPermissions($superAdmin, $allPermissions);
        $syncPermissions($admin, $allPermissions);

        $syncPermissions($manager, [
            'view_dashboard', 'reports.view',
            'orders.view', 'orders.create', 'orders.edit',
            'quotes.view', 'quotes.create', 'quotes.edit',
            'products.view', 'products.create', 'products.edit',
            'inventory.view', 'inventory.create', 'inventory.edit',
            'customers.view', 'customers.edit'
        ]);

        $syncPermissions($staff, [
            'view_dashboard', 'orders.view', 'orders.create', 'quotes.view', 'products.view', 'inventory.view'
        ]);

        $syncPermissions($sale, [
            'view_dashboard', 'reports.view', 'view_reports',
            'orders.view', 'orders.create', 'orders.edit', 'manage_orders',
            'quotes.view', 'quotes.create', 'quotes.edit',
            'customers.view', 'customers.create'
        ]);
    }
}
