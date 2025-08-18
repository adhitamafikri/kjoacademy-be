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
        $onboarding_category = CourseCategory::where('slug', '=', 'kjoacademy-onboarding')->first();
        $web3_fundamentals_category = CourseCategory::where('slug', '=', 'web-3-fundamentals')->first();
        $crypto_currency_trading_category = CourseCategory::where('slug', '=', 'crypto-currency-trading')->first();

        // Create sample courses
        $courses = [
            [
                'category_id' => $onboarding_category->id,
                'title' => 'Onboarding Course - KJO Academy',
                'slug' => 'onboarding-course-kjoacademy',
                'description' => 'Learn the basics of KJO Academy',
                'thumbnail_url' => 'https://placehold.co/400',
                'enrollment_count' => 0,
                'duration_seconds' => 3600,
                'is_published' => true,
            ],
            [
                'category_id' => $web3_fundamentals_category->id,
                'title' => 'How to create Web 3 Apps',
                'slug' => 'how-to-create-web-3-apps',
                'description' => 'Learn to create Web 3 Apps',
                'thumbnail_url' => 'https://placehold.co/400',
                'enrollment_count' => 0,
                'duration_seconds' => 3800,
                'is_published' => true,
            ],
            [
                'category_id' => $crypto_currency_trading_category->id,
                'title' => 'Fundamentals of Crypto Currency Trading',
                'slug' => 'fundamentals-of-crypto-currency-trading',
                'description' => 'Learn the basics of Crypto Currency Trading',
                'thumbnail_url' => 'https://placehold.co/400',
                'enrollment_count' => 0,
                'duration_seconds' => 900,
                'is_published' => true,
            ],
        ];

        foreach ($courses as $courseData) {
            Course::create($courseData);
        }
    }
}
