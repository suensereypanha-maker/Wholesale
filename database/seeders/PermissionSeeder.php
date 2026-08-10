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
            'view_dashboard'  => 'Can view admin dashboard',
            'manage_users'    => 'Can view and manage user accounts',
            'manage_roles'    => 'Can create, edit, and assign roles and permissions',
            'manage_orders'   => 'Can process and manage B2B wholesale orders',
            'manage_products' => 'Can manage inventory, products, and categories',
            'view_reports'    => 'Can view analytics, sales, and audit reports',
        ];

        foreach ($permissions as $name => $description) {
            Permission::updateOrCreate(
                ['name' => $name],
                ['guard_name' => 'web', 'description' => $description]
            );
        }
    }
}
