<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all possible permissions
        $allPermissions = [
            // System permissions
            'view dashboard',
            'manage system settings',
            
            // User management permissions
            'manage users',
            'assign roles',
            'assign permissions',
            
            // Content permissions
            'create content',
            'edit content',
            'delete content',
            'publish content',
            
            // Reporting permissions
            'view reports',
            'generate reports',
            'export reports',
            
            // Special permissions
            'access admin panel',
            'bypass approvals',
        ];

        // Create all permissions
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles with their base permissions
        $roles = [
            'ADMIN' => [
                'access admin panel',
                'manage system settings',
                'assign roles',
                'assign permissions',
            ],
            'HR' => [
                'manage users',
                'view reports',
                'generate reports',
            ],
            'PM' => [
                'create content',
                'edit content',
                'view reports',
            ],
            'Employee' => [
                'view dashboard',
                'create content',
            ],
            'Editor' => [
                'create content',
                'edit content',
                'publish content',
            ],
            'Viewer' => [
                'view dashboard',
                'view reports',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            
            // Assign base permissions
            $role->syncPermissions($permissions);
            
            // Admin gets all permissions
            if ($roleName === 'ADMIN') {
                $role->syncPermissions(Permission::all());
            }
        }

        // Create a developer role with all permissions if not in production
        if (app()->environment('local')) {
            $devRole = Role::firstOrCreate(['name' => 'Developer', 'guard_name' => 'web']);
            $devRole->syncPermissions(Permission::all());
        }
    }
}