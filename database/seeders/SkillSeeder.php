<?php

namespace Database\Seeders;

use App\Models\Skill;

use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Backend
            ['name' => 'Laravel', 'icon_path' => 'skills_logo/laravel_logo.png', 'category' => 'framework', 'proficiency' => 95, 'sort_order' => 1],
            ['name' => 'PHP', 'icon_path' => 'skills_logo/php_logo.png', 'category' => 'backend', 'proficiency' => 92, 'sort_order' => 2],
            ['name' => 'Livewire', 'icon_path' => 'skills_logo/livewire_logo.png', 'category' => 'framework', 'proficiency' => 90, 'sort_order' => 3],
            
            // Frontend
            ['name' => 'JavaScript', 'icon_path' => 'skills_logo/javascript_logo.png', 'category' => 'frontend', 'proficiency' => 88, 'sort_order' => 4],
            ['name' => 'TailwindCSS', 'icon_path' => 'skills_logo/tailwindcss_logo.png', 'category' => 'frontend', 'proficiency' => 90, 'sort_order' => 5],
            ['name' => 'Alpine.js', 'icon_path' => 'skills_logo/alpine_logo.png', 'category' => 'frontend', 'proficiency' => 85, 'sort_order' => 6],
            ['name' => 'HTML5', 'icon_path' => 'skills_logo/html_logo.png', 'category' => 'frontend', 'proficiency' => 95, 'sort_order' => 7],
            ['name' => 'CSS3', 'icon_path' => 'skills_logo/css_logo.png', 'category' => 'frontend', 'proficiency' => 90, 'sort_order' => 8],
            ['name' => 'JQuery', 'icon_path' => 'skills_logo/jquery_logo.png', 'category' => 'frontend', 'proficiency' => 90, 'sort_order' => 8],

            // Database
            ['name' => 'MySQL', 'icon_path' => 'skills_logo/mysql_logo.png', 'category' => 'database', 'proficiency' => 87, 'sort_order' => 9],
            
            // Others
            ['name' => 'Git', 'icon_path' => 'skills_logo/github_logo.png', 'category' => 'general', 'proficiency' => 88, 'sort_order' => 11],
            ['name' => 'Figma', 'icon_path' => 'skills_logo/figma_logo.png', 'category' => 'general', 'proficiency' => 75, 'sort_order' => 11],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
