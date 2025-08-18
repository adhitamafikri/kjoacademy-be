<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasUlids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'onboarding_completed_at',
        'onboarding_started_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'onboarding_completed_at' => 'datetime',
        'onboarding_started_at' => 'datetime',
    ];

    // Role relationship
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Relationships
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    public function activeEnrollments()
    {
        return $this->hasMany(Enrollment::class, 'user_id')->whereNotIn('status', ['dropped']);
    }

    public function completedEnrollments()
    {
        return $this->hasMany(Enrollment::class, 'user_id')->where('status', 'completed');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id')
                    ->withPivot(['status', 'progress_percentage', 'enrolled_at', 'completed_at'])
                    ->withTimestamps();
    }

    // Progress relationships
    public function moduleProgress()
    {
        return $this->hasMany(ModuleProgress::class, 'user_id');
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class, 'user_id');
    }

    // Onboarding relationships
    public function onboardingProgress()
    {
        return $this->hasMany(OnboardingProgress::class, 'user_id');
    }

    // Onboarding methods
    public function hasCompletedOnboarding()
    {
        return !is_null($this->onboarding_completed_at);
    }

    public function hasStartedOnboarding()
    {
        return !is_null($this->onboarding_started_at);
    }

    public function canAccessNonOnboardingContent()
    {
        return $this->hasCompletedOnboarding();
    }

    public function getOnboardingProgressPercentage()
    {
        $onboardingCategory = CourseCategory::where('slug', 'kjoacademy-onboarding')->first();
        
        if (!$onboardingCategory) {
            return 0;
        }

        $totalOnboardingCourses = $onboardingCategory->courses()->count();
        $completedOnboardingCourses = $this->onboardingProgress()
            ->where('status', OnboardingProgress::STATUS_COMPLETED)
            ->count();

        return $totalOnboardingCourses > 0 ? round(($completedOnboardingCourses / $totalOnboardingCourses) * 100) : 0;
    }

    public function getCompletedOnboardingCourses()
    {
        return $this->onboardingProgress()
            ->where('status', OnboardingProgress::STATUS_COMPLETED)
            ->with('onboardingCourse')
            ->get();
    }

    public function getInProgressOnboardingCourses()
    {
        return $this->onboardingProgress()
            ->where('status', OnboardingProgress::STATUS_IN_PROGRESS)
            ->with('onboardingCourse')
            ->get();
    }

    public function startOnboarding()
    {
        if (!$this->hasStartedOnboarding()) {
            $this->update(['onboarding_started_at' => now()]);
        }
    }

    public function completeOnboarding()
    {
        $this->update(['onboarding_completed_at' => now()]);
    }
}
