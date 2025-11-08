<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Create a user with a specific role
     */
    protected function createUserWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($role);
        return $user;
    }

    /**
     * Create roles and permissions for testing
     */
    protected function setUpRoles(): void
    {
        $permissions = [
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view tickets', 'guard_name' => 'web']),
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'edit tickets', 'guard_name' => 'web']),
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view customers', 'guard_name' => 'web']),
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view users', 'guard_name' => 'web']),
        ];

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions);

        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions([
            'view tickets',
            'edit tickets',
            'view customers',
        ]);
    }
}
