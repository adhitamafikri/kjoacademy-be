<?php

namespace Database\Seeders;

use App\Models\CourseCategory;
use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Programming',
                'description' => 'Learn various programming languages and frameworks',
            ],
            [
                'title' => 'Design',
                'description' => 'Master design principles and tools',
            ],
            [
                'title' => 'Business',
                'description' => 'Develop business and management skills',
            ],
            [
                'title' => 'Marketing',
                'description' => 'Learn digital and traditional marketing strategies',
            ],
        ];

        foreach ($categories as $category) {
            CourseCategory::create($category);
        }
    }
}
