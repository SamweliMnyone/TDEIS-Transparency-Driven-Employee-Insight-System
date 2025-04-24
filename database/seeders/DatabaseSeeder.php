<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Skill;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Clear tables in correct order (child tables first)
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('skills')->truncate();
        DB::table('users')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ======================
        // 1. CREATE PERMISSIONS
        // ======================
        $permissions = [
            // System permissions
            'access_dashboard',
            'manage_system',

            // User management permissions
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'manage_roles',
            'manage_permissions',

            // Content permissions
            'create_content',
            'edit_content',
            'delete_content',
            'publish_content',

            // HR specific permissions
            'manage_hr',
            'view_salaries',

            // Project management permissions
            'manage_projects',
            'assign_tasks',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
        // ======================
        // 2. CREATE ROLES
        // ======================
        $roles = [
            'ADMIN' => [
                // ADMIN gets all permissions
                'permissions' => Permission::all()->pluck('name')->toArray()
            ],
            'HR' => [
                'permissions' => [
                    'access_dashboard',
                    'view_users',
                    'create_users',
                    'edit_users',
                    'manage_hr',
                    'view_salaries'
                ]
            ],
            'PM' => [
                'permissions' => [
                    'access_dashboard',
                    'manage_projects',
                    'assign_tasks',
                    'create_content',
                    'edit_content'
                ]
            ],
            'Employee' => [
                'permissions' => [
                    'access_dashboard',
                    'create_content'
                ]
            ]
        ];

        foreach ($roles as $roleName => $roleData) {
            $role = Role::create([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            $role->givePermissionTo($roleData['permissions']);
        }

        // ======================
        // 3. CREATE USERS
        // ======================
        // Create specific known users first with default password 'password'
        $admin = User::factory()->create([
            'name'  => 'System Admin',
            'email' => 'admin@example.com',
            'role'  => 'ADMIN',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('ADMIN');

        $hrManager = User::factory()->create([
            'name'  => 'HR Manager',
            'email' => 'hr@example.com',
            'role'  => 'HR',
            'password' => bcrypt('password'),
        ]);
        $hrManager->assignRole('HR');

        $projectManager = User::factory()->create([
            'name'  => 'Project Manager',
            'email' => 'pm@example.com',
            'role'  => 'PM',
            'password' => bcrypt('password'),
        ]);
        $projectManager->assignRole('PM');

        // Create random users
        $users = User::factory(97)->create();

        // Assign roles and permissions to random users
        $users->each(function ($user) use ($roles) {
            // Exclude ADMIN from random assignment
            $assignableRoles = array_diff(array_keys($roles), ['ADMIN']);
            if (empty($assignableRoles)) {
                return;
            }
            $roleName = $assignableRoles[array_rand($assignableRoles)];

            $user->assignRole($roleName);
            $user->update(['role' => $roleName]);

            // Optionally assign additional permissions beyond role defaults (50% chance)
            if (rand(0, 1)) {
                $extraPermissions = Permission::whereNotIn('name', $roles[$roleName]['permissions'])
                    ->inRandomOrder()
                    ->limit(rand(1, 3))
                    ->pluck('name')
                    ->toArray();

                $user->givePermissionTo($extraPermissions);
            }

            // Create skills for each user
            $this->createUserSkills($user);
        });

        // Optional: Print seeded users info to console (for development)
        if ($this->command) {
            $this->command->info('Seeded users:');
            $this->command->info('Admin: admin@example.com / password');
            $this->command->info('HR Manager: hr@example.com / password');
            $this->command->info('Project Manager: pm@example.com / password');
        }
    }

    /**
     * Create sample skills for a user
     */
    protected function createUserSkills(User $user)
    {
        $skills = [
            [
                'skill_name'         => 'PHP',
                'proficiency'        => ['Beginner', 'Intermediate', 'Advanced'][rand(0, 2)],
                'years_of_experience'=> rand(1, 10),
                'description'        => 'Web development with Laravel'
            ],
            [
                'skill_name'         => 'JavaScript',
                'proficiency'        => ['Beginner', 'Intermediate', 'Advanced'][rand(0, 2)],
                'years_of_experience'=> rand(1, 10),
                'description'        => 'Frontend development with Vue.js/React'
            ],
            [
                'skill_name'         => 'MySQL',
                'proficiency'        => ['Beginner', 'Intermediate', 'Advanced'][rand(0, 2)],
                'years_of_experience'=> rand(1, 8),
                'description'        => 'Database design and optimization'
            ],
            [
                'skill_name'         => 'Project Management',
                'proficiency'        => ['Beginner', 'Intermediate', 'Advanced'][rand(0, 2)],
                'years_of_experience'=> rand(1, 5),
                'description'        => 'Agile project management'
            ]
        ];

        foreach ($skills as $skillData) {
            Skill::create(array_merge($skillData, ['user_id' => $user->id]));
        }
    }
}
