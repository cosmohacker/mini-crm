<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        $admin = User::firstOrCreate(
            [
                'email'     => 'admin@admin.com',
                'name'      => 'Admin',
                'password'  => bcrypt('Admin'),
            ]
        );

        // Assign admin role
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Create Manager user
        $manager = User::firstOrCreate(
            [
                'email'     => 'manager@manager.com',
                'name'      => 'Manager',
                'password'  => bcrypt('Manager'),
            ]
        );

        // Assign manager role
        if (!$manager->hasRole('manager')) {
            $manager->assignRole('manager');
        }
    }
}
