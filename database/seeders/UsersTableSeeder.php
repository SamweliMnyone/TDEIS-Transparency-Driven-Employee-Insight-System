<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use App\Models\Skill; // Add this import

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key constraints
        Schema::disableForeignKeyConstraints();
        
        // Clear tables in correct order
        DB::table('skills')->truncate();
        DB::table('users')->truncate();
        
        // Enable foreign key constraints
        Schema::enableForeignKeyConstraints();
        
        // Create permissions if they don't exist
        $permissions = [
            Permission::firstOrCreate(['name' => 'view dashboard', 'guard_name' => 'web']),
            Permission::firstOrCreate(['name' => 'edit content', 'guard_name' => 'web']),
            Permission::firstOrCreate(['name' => 'manage users', 'guard_name' => 'web']),
        ];
        
        // Now seed your users
        \App\Models\User::factory()->count(100)->create()->each(function ($user) use ($permissions) {
            // Assign permissions using Spatie's method
            $user->givePermissionTo($permissions);
            
            // Create skills using the Skill factory
            Skill::factory()->count(3)->create([
                'user_id' => $user->id
            ]);
        });
    }
}