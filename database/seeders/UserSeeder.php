<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@wholesale.com',
                'name'  => 'Super Admin',
                'role'  => 'Super Admin',
            ],
            [
                'email' => 'sarah.ops@wholesale.com',
                'name'  => 'Sarah Jenkins',
                'role'  => 'Admin',
            ],
            [
                'email' => 'david.sales@wholesale.com',
                'name'  => 'David Kim',
                'role'  => 'Manager',
            ],
            [
                'email' => 'lisa.support@wholesale.com',
                'name'  => 'Lisa Vance',
                'role'  => 'Staff',
            ],
            [
                'email' => 'jane@pacifichardware.com',
                'name'  => 'Jane Smith',
                'role'  => 'User',
                'company' => 'Pacific Hardware Distributors Co.',
                'tax_number' => 'VAT-987654321',
                'phone' => '+1 (555) 345-6789',
                'address' => '100 Tech Enterprise Way',
                'city' => 'San Jose',
                'province' => 'California',
                'zip' => '95134',
                'country' => 'United States',
                'tier' => 'VIP Platinum Wholesale',
                'credit_limit' => 50000.00,
                'wholesale_discount' => 15.00,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'company' => $userData['company'] ?? null,
                    'tax_number' => $userData['tax_number'] ?? null,
                    'phone' => $userData['phone'] ?? null,
                    'address' => $userData['address'] ?? null,
                    'city' => $userData['city'] ?? null,
                    'province' => $userData['province'] ?? null,
                    'zip' => $userData['zip'] ?? null,
                    'country' => $userData['country'] ?? null,
                    'tier' => $userData['tier'] ?? 'Standard Wholesale',
                    'credit_limit' => $userData['credit_limit'] ?? 0.00,
                    'wholesale_discount' => $userData['wholesale_discount'] ?? 0.00,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'status' => 'active',
                ]
            );

            if ($userData['role']) {
                $role = Role::where('name', $userData['role'])->first();
                if ($role) {
                    $user->assignRole($role);
                }
            }
        }
    }
}
