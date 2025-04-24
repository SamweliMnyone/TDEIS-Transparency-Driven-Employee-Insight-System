<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    protected static $skillIndex = 0;
    
    protected static $skills = [
        'PHP', 'JavaScript', 'Python', 'Java', 'C#', 'C++', 'Ruby', 'Go', 'Swift', 'Kotlin',
        'HTML', 'CSS', 'React', 'Angular', 'Vue', 'Node.js', 'Laravel', 'Django', 'Spring', 'Flask',
        'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'SQLite', 'Oracle', 'SQL Server',
        'Docker', 'Kubernetes', 'AWS', 'Azure', 'GCP', 'Git', 'CI/CD', 'REST API', 'GraphQL',
        'Machine Learning', 'Data Science', 'AI', 'Blockchain', 'Cybersecurity', 'DevOps',
        'UI/UX Design', 'Project Management', 'Agile', 'Scrum', 'Technical Writing',
        // Add more skills as needed up to 100+ items
        'TypeScript', 'Express.js', 'NestJS', 'Next.js', 'Nuxt.js', 'jQuery', 'Bootstrap',
        'Tailwind CSS', 'SASS', 'LESS', 'Webpack', 'Babel', 'Jest', 'Mocha', 'Chai',
        'JUnit', 'PyTest', 'RSpec', 'Cypress', 'Selenium', 'Jenkins', 'Travis CI',
        'GitHub Actions', 'Ansible', 'Terraform', 'Puppet', 'Chef', 'Linux', 'Windows Server',
        'MacOS', 'iOS', 'Android', 'Flutter', 'React Native', 'Xamarin', 'Unity', 'Unreal Engine',
        'Photoshop', 'Illustrator', 'Figma', 'Sketch', 'Adobe XD', 'Blender', 'Maya',
        '3ds Max', 'ZBrush', 'Substance Painter', 'After Effects', 'Premiere Pro', 'Final Cut Pro',
        'DaVinci Resolve', 'Logic Pro', 'Pro Tools', 'Ableton Live', 'FL Studio', 'Audacity'
    ];

    public function definition()
    {
        return [
            'skill_name' => $this->getNextSkill(),
            'proficiency' => $this->faker->randomElement(['Beginner', 'Intermediate', 'Advanced', 'Expert']),
            'years_of_experience' => $this->faker->numberBetween(1, 10),
            'description' => $this->faker->sentence,
            'user_id' => \App\Models\User::factory(),
        ];
    }

    protected function getNextSkill()
    {
        // If we've used all unique skills, start appending numbers
        if (self::$skillIndex >= count(self::$skills)) {
            $baseSkill = self::$skills[array_rand(self::$skills)];
            return $baseSkill . ' ' . (self::$skillIndex - count(self::$skills) + 1);
        }

        return self::$skills[self::$skillIndex++];
    }
}