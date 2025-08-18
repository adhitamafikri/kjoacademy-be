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
                'title' => 'Onboarding',
                'slug' => 'kjoacademy-onboarding',
                'description' => 'Learn various programming languages and frameworks',
            ],
            [
                'title' => 'Web 3 Fundamentals',
                'slug' => 'web-3-fundamentals',
                'description' => 'Master Web 3 Fundamentals',
            ],
            [
                'title' => 'Crypto Currency Trading',
                'slug' => 'crypto-currency-trading',
                'description' => 'Learn the basics of crypto currency trading',
            ],
            [
                'title' => 'Blockchain Fundamentals',
                'slug' => 'blockchain-fundamentals',
                'description' => 'Learn the basics of blockchain technology',
            ],
            [
                'title' => 'Smart Contracts',
                'slug' => 'smart-contracts',
                'description' => 'Learn to develop and deploy smart contracts',
            ],
            [
                'title' => 'DeFi (Decentralized Finance)',
                'slug' => 'defi-decentralized-finance',
                'description' => 'Explore decentralized finance protocols and applications',
            ],
        ];

        foreach ($categories as $category) {
            CourseCategory::create($category);
        }
    }
}
