<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        // Get students only (exclude admin)
        $students = User::whereHas('role', function ($query) {
            $query->where('name', 'student');
        })->take(6)->get(); // Get 6 students (including the original one)
        
        $courses = Course::all();

        // Check if we have enough students and courses
        if ($students->count() < 3) {
            throw new \Exception('Need at least 3 students to create enrollment data');
        }

        if ($courses->count() < 3) {
            throw new \Exception('Need at least 3 courses to create enrollment data');
        }

        // Create realistic enrollment scenarios
        $enrollmentData = [
            // Student 1: Enrolled in multiple courses with different progress
            [
                'user_id' => $students[0]->id,
                'course_id' => $courses[0]->id, // Onboarding course
                'status' => Enrollment::STATUS_COMPLETED,
                'progress_percentage' => 100,
                'enrolled_at' => now()->subDays(45),
                'completed_at' => now()->subDays(30),
                'last_accessed_at' => now()->subDays(30),
            ],
            [
                'user_id' => $students[0]->id,
                'course_id' => $courses[1]->id, // Web 3 course
                'status' => Enrollment::STATUS_IN_PROGRESS,
                'progress_percentage' => 65,
                'enrolled_at' => now()->subDays(20),
                'completed_at' => null,
                'last_accessed_at' => now()->subDays(2),
            ],
        ];

        // Add more enrollments only if we have enough courses
        if ($courses->count() >= 3) {
            $enrollmentData[] = [
                'user_id' => $students[0]->id,
                'course_id' => $courses[2]->id, // Crypto trading course
                'status' => Enrollment::STATUS_ENROLLED,
                'progress_percentage' => 0,
                'enrolled_at' => now()->subDays(5),
                'completed_at' => null,
                'last_accessed_at' => now()->subDays(5),
            ];
        }

        // Student 2: Focused on one course
        if ($courses->count() >= 4) {
            $enrollmentData[] = [
                'user_id' => $students[1]->id,
                'course_id' => $courses[3]->id, // Smart contract development
                'status' => Enrollment::STATUS_IN_PROGRESS,
                'progress_percentage' => 85,
                'enrolled_at' => now()->subDays(60),
                'completed_at' => null,
                'last_accessed_at' => now()->subDays(1),
            ];
        } else {
            // Fallback to first course if not enough courses
            $enrollmentData[] = [
                'user_id' => $students[1]->id,
                'course_id' => $courses[0]->id,
                'status' => Enrollment::STATUS_IN_PROGRESS,
                'progress_percentage' => 85,
                'enrolled_at' => now()->subDays(60),
                'completed_at' => null,
                'last_accessed_at' => now()->subDays(1),
            ];
        }

        // Student 3: Multiple completed courses
        if ($students->count() >= 3) {
            $enrollmentData[] = [
                'user_id' => $students[2]->id,
                'course_id' => $courses[0]->id, // Onboarding course
                'status' => Enrollment::STATUS_COMPLETED,
                'progress_percentage' => 100,
                'enrolled_at' => now()->subDays(90),
                'completed_at' => now()->subDays(75),
                'last_accessed_at' => now()->subDays(75),
            ];
            $enrollmentData[] = [
                'user_id' => $students[2]->id,
                'course_id' => $courses[1]->id, // Web 3 course
                'status' => Enrollment::STATUS_COMPLETED,
                'progress_percentage' => 100,
                'enrolled_at' => now()->subDays(80),
                'completed_at' => now()->subDays(60),
                'last_accessed_at' => now()->subDays(60),
            ];

            // Add DeFi course enrollment only if we have enough courses
            if ($courses->count() >= 5) {
                $enrollmentData[] = [
                    'user_id' => $students[2]->id,
                    'course_id' => $courses[4]->id, // DeFi course
                    'status' => Enrollment::STATUS_IN_PROGRESS,
                    'progress_percentage' => 40,
                    'enrolled_at' => now()->subDays(30),
                    'completed_at' => null,
                    'last_accessed_at' => now()->subDays(3),
                ];
            }
        }

        // Student 4: Just started learning
        if ($students->count() >= 4) {
            $enrollmentData[] = [
                'user_id' => $students[3]->id,
                'course_id' => $courses[0]->id, // Onboarding course
                'status' => Enrollment::STATUS_IN_PROGRESS,
                'progress_percentage' => 25,
                'enrolled_at' => now()->subDays(10),
                'completed_at' => null,
                'last_accessed_at' => now()->subDays(1),
            ];
        }

        // Student 5: Dropped a course
        if ($students->count() >= 5) {
            if ($courses->count() >= 3) {
                $enrollmentData[] = [
                    'user_id' => $students[4]->id,
                    'course_id' => $courses[2]->id, // Crypto trading course
                    'status' => Enrollment::STATUS_DROPPED,
                    'progress_percentage' => 15,
                    'enrolled_at' => now()->subDays(25),
                    'completed_at' => null,
                    'last_accessed_at' => now()->subDays(20),
                ];
            }
            $enrollmentData[] = [
                'user_id' => $students[4]->id,
                'course_id' => $courses[1]->id, // Web 3 course
                'status' => Enrollment::STATUS_IN_PROGRESS,
                'progress_percentage' => 55,
                'enrolled_at' => now()->subDays(15),
                'completed_at' => null,
                'last_accessed_at' => now()->subDays(1),
            ];
        }

        // Create enrollments
        foreach ($enrollmentData as $enrollment) {
            Enrollment::create($enrollment);
        }

        // Create some additional random enrollments for variety
        $additionalEnrollments = 10;
        for ($i = 0; $i < $additionalEnrollments; $i++) {
            $user = $students->random();
            $course = $courses->random();
            
            // Check if this user is already enrolled in this course
            $existingEnrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->exists();
            
            if (!$existingEnrollment) {
                $status = ['enrolled', 'in_progress', 'completed'][rand(0, 2)];
                $progressPercentage = $status === 'completed' ? 100 : ($status === 'in_progress' ? rand(10, 90) : 0);
                
                Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'status' => $status,
                    'progress_percentage' => $progressPercentage,
                    'enrolled_at' => now()->subDays(rand(1, 100)),
                    'completed_at' => $status === 'completed' ? now()->subDays(rand(0, 30)) : null,
                    'last_accessed_at' => now()->subDays(rand(0, 7)),
                ]);
            }
        }
    }
}
