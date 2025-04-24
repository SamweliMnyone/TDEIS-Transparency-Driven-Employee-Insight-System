<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Skill;

class SkillsTableSeeder extends Seeder
{
    public function run()
    {
        // Get all users or create some if none exist
        $users = User::all();
        
        if ($users->isEmpty()) {
            // Create 10 users if none exist
            $users = User::factory()->count(100)->create();
        }

        // Define common skills
        $commonSkills = [
            'PHP', 'JavaScript', 'Python', 'Java', 'C#',
            'HTML/CSS', 'React', 'Vue.js', 'Laravel', 'Django',
            'MySQL', 'PostgreSQL', 'Git', 'Docker', 'AWS'
        ];

        foreach ($users as $user) {
            // Create 3-5 random skills for each user
            $skillCount = rand(3, 5);
            
            for ($i = 0; $i < $skillCount; $i++) {
                Skill::create([
                    'user_id' => $user->id,
                    'skill_name' => $commonSkills[array_rand($commonSkills)],
                    'proficiency' => $this->randomProficiency(),
                    'years_of_experience' => rand(1, 10),
                    'description' => $this->randomDescription()
                ]);
            }
        }
    }

    protected function randomProficiency()
    {
        $levels = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];
        return $levels[array_rand($levels)];
    }

    protected function randomDescription()
    {
        $descriptions = [
            'Worked on multiple projects using this skill',
            'Main skill used in current position',
            'Self-taught through online courses',
            'Certified in this technology',
            'Used professionally for several years',
            null // Sometimes return null for variety
        ];
        
        return $descriptions[array_rand($descriptions)];
    }
}