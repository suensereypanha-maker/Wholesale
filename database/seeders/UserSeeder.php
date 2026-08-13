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
                'email' => 'sale@wholesale.com',
                'name'  => 'Sale Representative',
                'role'  => 'Sale',
                'status' => 'active',
            ],
            [
                'email' => 'sales@wholesale.com',
                'name'  => 'Sales Executive',
                'role'  => 'Sale',
                'status' => 'active',
            ],
            [
                'email' => 'jane@pacifichardware.com',
                'name'  => 'Jane Smith',
                'role'  => 'User',
                'status' => 'active',
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
            [
                'email' => 'mchang@apextech.com',
                'name'  => 'Michael Chang',
                'role'  => 'User',
                'status' => 'pending',
                'company' => 'Apex Technology Wholesale',
                'tax_number' => 'TAX-55443322',
                'phone' => '+1 (555) 888-9900',
                'address' => '450 Industrial Parkway',
                'city' => 'Austin',
                'province' => 'Texas',
                'zip' => '78701',
                'country' => 'United States',
                'tier' => 'Standard Wholesale',
                'credit_limit' => 0.00,
                'wholesale_discount' => 0.00,
            ],
            [
                'email' => 'smartinez@globalbuy.com',
                'name'  => 'Sophia Martinez',
                'role'  => 'User',
                'status' => 'pending',
                'company' => 'Global Logistics & Buying',
                'tax_number' => 'TAX-99887711',
                'phone' => '+1 (555) 777-1122',
                'address' => '88 Commerce Blvd',
                'city' => 'Miami',
                'province' => 'Florida',
                'zip' => '33101',
                'country' => 'United States',
                'tier' => 'Standard Wholesale',
                'credit_limit' => 0.00,
                'wholesale_discount' => 0.00,
            ],
            [
                'email' => 'robert.chen@pacifictech.com',
                'name'  => 'Robert Chen',
                'role'  => 'User',
                'status' => 'rejected',
                'company' => 'Unverified Retail Outlets',
                'tax_number' => null,
                'phone' => '+1 (555) 444-3322',
                'address' => '12 Fake Street',
                'city' => 'Seattle',
                'province' => 'Washington',
                'zip' => '98101',
                'country' => 'United States',
                'tier' => 'Standard Wholesale',
                'credit_limit' => 0.00,
                'wholesale_discount' => 0.00,
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
                    'status' => $userData['status'] ?? 'active',
                ]
            );

            if (!empty($userData['role'])) {
                $role = Role::where('name', $userData['role'])->first();
                if ($role) {
                    $user->assignRole($role);
                }
            }
        }
    }
}
