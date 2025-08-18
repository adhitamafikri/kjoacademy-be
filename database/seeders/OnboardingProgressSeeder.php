<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\OnboardingProgress;
use App\Models\User;
use Illuminate\Database\Seeder;

class OnboardingProgressSeeder extends Seeder
{
    public function run(): void
    {
        // Get onboarding category and courses
        $onboardingCategory = CourseCategory::where('slug', 'kjoacademy-onboarding')->first();
        
        if (!$onboardingCategory) {
            return; // No onboarding category found
        }

        $onboardingCourses = $onboardingCategory->courses()->get();
        
        if ($onboardingCourses->isEmpty()) {
            return; // No onboarding courses found
        }

        // Get students only
        $students = User::whereHas('role', function ($query) {
            $query->where('name', 'student');
        })->get();

        foreach ($students as $student) {
            // Randomly decide if student has started onboarding
            $hasStartedOnboarding = rand(0, 1);
            
            if ($hasStartedOnboarding) {
                $student->startOnboarding();
                
                foreach ($onboardingCourses as $course) {
                    // Randomly decide if student has started this onboarding course
                    $hasStartedCourse = rand(0, 1);
                    
                    if ($hasStartedCourse) {
                        $progressPercentage = rand(0, 100);
                        $status = $progressPercentage >= 100 ? 'completed' : ($progressPercentage > 0 ? 'in_progress' : 'not_started');
                        
                        OnboardingProgress::create([
                            'user_id' => $student->id,
                            'onboarding_course_id' => $course->id,
                            'status' => $status,
                            'progress_percentage' => $progressPercentage,
                            'started_at' => $progressPercentage > 0 ? now()->subDays(rand(1, 30)) : null,
                            'completed_at' => $status === 'completed' ? now()->subDays(rand(0, 10)) : null,
                            'last_accessed_at' => $progressPercentage > 0 ? now()->subDays(rand(0, 7)) : null,
                        ]);
                    }
                }

                // Check if all onboarding courses are completed and update user status
                $completedCourses = $student->onboardingProgress()
                    ->where('status', OnboardingProgress::STATUS_COMPLETED)
                    ->count();

                if ($completedCourses >= $onboardingCourses->count()) {
                    $student->completeOnboarding();
                }
            }
        }
    }
}
