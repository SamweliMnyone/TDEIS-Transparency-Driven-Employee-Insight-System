<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Skill;
use App\Models\Project; // Add this line

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
        DB::table('projects')->truncate(); // Add this line
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


            // User management permissions
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'create_content',
            // Project management permissions

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

                ]
            ],
            'PM' => [
                'permissions' => [
                    'access_dashboard',
                    'assign_tasks',

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
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'role' => 'ADMIN',
            'password' => bcrypt('password'),
            'phone' => '+255712345678', // Tanzania phone number
            'address' => 'Dar es Salaam, Tanzania'
        ]);
        $admin->assignRole('ADMIN');

        $hrManager = User::factory()->create([
            'name' => 'HR Manager',
            'email' => 'hr@example.com',
            'role' => 'HR',
            'password' => bcrypt('password'),
            'phone' => '+255712345679', // Tanzania phone number
            'address' => 'Arusha, Tanzania'
        ]);
        $hrManager->assignRole('HR');

        $projectManager = User::factory()->create([
            'name' => 'Project Manager',
            'email' => 'pm@example.com',
            'role' => 'PM',
            'password' => bcrypt('password'),
            'phone' => '+255712345670', // Tanzania phone number
            'address' => 'Mwanza, Tanzania'
        ]);
        $projectManager->assignRole('PM');

        $employee = User::factory()->create([
            'name' => 'Employee',
            'email' => 'employee@example.com',
            'role' => 'Employee',
            'password' => bcrypt('password'),
            'phone' => '+255712345670', // Tanzania phone number
            'address' => 'Mwanza, Tanzania'
        ]);
        $employee->assignRole('PM');

        // Create random users with Tanzania details
        $users = User::factory(97)->create([
            'phone' => function () {
                return '+2557' . rand(10, 99) . rand(100000, 999999);
            },
            'address' => function () {
                $cities = ['Dar es Salaam', 'Mwanza', 'Arusha', 'Dodoma', 'Mbeya', 'Morogoro', 'Tanga', 'Zanzibar'];
                return $cities[array_rand($cities)] . ', Tanzania';
            },

            'password' => bcrypt('password'), // Default password for all users


        ]);

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

        // ======================
        // 4. CREATE PROJECTS (Tanzania-specific)
        // ======================
        $tanzaniaProjects = [
            [
                'name' => 'Dar es Salaam Port Expansion',
                'objective' => 'Expand and modernize the Dar es Salaam port to increase capacity and efficiency',
                'scope' => 'Construction of new berths, dredging, and installation of modern port equipment',
                'estimated_time' => '13',
                'estimated_cost' => 1500000000,
                'project_manager_id' => $projectManager->id
            ],
            [
                'name' => 'Standard Gauge Railway Project',
                'objective' => 'Develop a modern railway network connecting Dar es Salaam to neighboring countries',
                'scope' => 'Construction of railway tracks, stations, and related infrastructure',
                'estimated_time' => '9',
                'estimated_cost' => 7000000000,
                'project_manager_id' => $projectManager->id
            ],
            [
                'name' => 'Rufiji Hydropower Project',
                'objective' => 'Build a large-scale hydropower plant to increase Tanzania\'s electricity generation capacity',
                'scope' => 'Dam construction, power plant installation, and transmission lines',
                'estimated_time' => '15',
                'estimated_cost' => 3900000000,
                'project_manager_id' => $projectManager->id
            ],
            [
                'name' => 'Zanzibar Smart City Initiative',
                'objective' => 'Develop smart city infrastructure in Zanzibar to boost tourism and local economy',
                'scope' => 'Digital infrastructure, smart utilities, and urban planning',
                'estimated_time' => '18',
                'estimated_cost' => 850000000,
                'project_manager_id' => $projectManager->id
            ],
            [
                'name' => 'Tanzania National Fiber Optic Network',
                'objective' => 'Expand broadband connectivity across Tanzania',
                'scope' => 'Laying fiber optic cables across the country',
                'estimated_time' => '12',
                'estimated_cost' => 450000000,
                'project_manager_id' => $projectManager->id
            ]
        ];

        foreach ($tanzaniaProjects as $projectData) {
            Project::create($projectData);
        }

        // Optional: Print seeded users info to console (for development)
        if ($this->command) {
            $this->command->info('Seeded users:');
            $this->command->info('Admin: admin@example.com / password');
            $this->command->info('HR Manager: hr@example.com / password');
            $this->command->info('Project Manager: pm@example.com / password');
            $this->command->info('Seeded 5 Tanzania-specific projects');
        }
    }

    /**
     * Create sample skills for a user
     */
    protected function createUserSkills(User $user)
    {
        $skills = [
            [
                'skill_name' => 'PHP',
                'proficiency' => ['Beginner', 'Intermediate', 'Advanced'][rand(0, 2)],
                'years_of_experience' => rand(1, 10),
                'description' => 'Web development with Laravel'
            ],
            [
                'skill_name' => 'JavaScript',
                'proficiency' => ['Beginner', 'Intermediate', 'Advanced'][rand(0, 2)],
                'years_of_experience' => rand(1, 10),
                'description' => 'Frontend development with Vue.js/React'
            ],
            [
                'skill_name' => 'MySQL',
                'proficiency' => ['Beginner', 'Intermediate', 'Advanced'][rand(0, 2)],
                'years_of_experience' => rand(1, 8),
                'description' => 'Database design and optimization'
            ],
            [
                'skill_name' => 'Project Management',
                'proficiency' => ['Beginner', 'Intermediate', 'Advanced'][rand(0, 2)],
                'years_of_experience' => rand(1, 5),
                'description' => 'Agile project management'
            ]
        ];

        foreach ($skills as $skillData) {
            Skill::create(array_merge($skillData, ['user_id' => $user->id]));
        }
    }
}
