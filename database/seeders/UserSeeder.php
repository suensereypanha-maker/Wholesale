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
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
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
