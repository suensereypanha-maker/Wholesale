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
        // Define Roles
        $superAdmin = Role::updateOrCreate(
            ['name' => 'Super Admin'],
            ['guard_name' => 'web', 'description' => 'Full unrestricted system access']
        );

        $admin = Role::updateOrCreate(
            ['name' => 'Admin'],
            ['guard_name' => 'web', 'description' => 'Administrative access to manage operations']
        );

        $manager = Role::updateOrCreate(
            ['name' => 'Manager'],
            ['guard_name' => 'web', 'description' => 'Managerial access for orders and products']
        );

        $staff = Role::updateOrCreate(
            ['name' => 'Staff'],
            ['guard_name' => 'web', 'description' => 'Basic staff access to view orders and dashboard']
        );

        $userRole = Role::updateOrCreate(
            ['name' => 'User'],
            ['guard_name' => 'web', 'description' => 'Standard B2B Customer account']
        );

        // Assign Permissions
        $allPermissions = Permission::pluck('name')->toArray();

        $superAdmin->givePermissionTo($allPermissions);
        $admin->givePermissionTo(['view_dashboard', 'manage_users', 'manage_orders', 'manage_products', 'view_reports']);
        $manager->givePermissionTo(['view_dashboard', 'manage_orders', 'manage_products']);
        $staff->givePermissionTo(['view_dashboard', 'manage_orders']);
    }
}
