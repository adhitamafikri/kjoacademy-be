<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = CourseCategory::all();
        $instructors = User::where('role_id', 2)->get(); // Assuming role_id 2 is instructor

        if ($categories->isEmpty() || $instructors->isEmpty()) {
            $this->command->warn('Please run CourseCategorySeeder and create instructors first!');
            return;
        }

        // Create sample courses
        $courses = [
            [
                'title' => 'Laravel Fundamentals',
                'description' => 'Learn the basics of Laravel framework',
                'price' => 49.99,
                'difficulty_level' => 'beginner',
                'duration_minutes' => 180,
            ],
            [
                'title' => 'Advanced PHP Patterns',
                'description' => 'Master advanced PHP design patterns',
                'price' => 79.99,
                'difficulty_level' => 'advanced',
                'duration_minutes' => 240,
            ],
            [
                'title' => 'Vue.js for Beginners',
                'description' => 'Start your journey with Vue.js',
                'price' => 39.99,
                'difficulty_level' => 'beginner',
                'duration_minutes' => 120,
            ],
        ];

        foreach ($courses as $courseData) {
            Course::create(array_merge($courseData, [
                'category_id' => $categories->random()->id,
                'instructor_id' => $instructors->random()->id,
                'is_published' => true,
            ]));
        }

        // Create additional random courses
        Course::factory(20)->create();
    }
}
