<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create permissions
        $permissions = [
            Permission::firstOrCreate(['name' => 'view tickets']),
            Permission::firstOrCreate(['name' => 'edit tickets']),
            Permission::firstOrCreate(['name' => 'view customers']),
            Permission::firstOrCreate(['name' => 'view users']),
        ];

        // Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions([
            'view tickets',
            'edit tickets',
            'view customers',
        ]);
    }
}

