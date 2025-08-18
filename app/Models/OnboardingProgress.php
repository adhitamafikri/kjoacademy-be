<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnboardingProgress extends Model
{
    /** @use HasFactory<\Database\Factories\OnboardingProgressFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'onboarding_progress';

    protected $fillable = [
        'user_id',
        'onboarding_course_id',
        'status',
        'progress_percentage',
        'started_at',
        'completed_at',
        'last_accessed_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'progress_percentage' => 'integer',
    ];

    // Status constants
    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function onboardingCourse()
    {
        return $this->belongsTo(Course::class, 'onboarding_course_id');
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('onboarding_course_id', $courseId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['user', 'onboardingCourse']);
    }

    // Status checking methods
    public function isNotStarted()
    {
        return $this->status === self::STATUS_NOT_STARTED;
    }

    public function isInProgress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    // Progress methods
    public function updateProgress($percentage)
    {
        $this->update([
            'progress_percentage' => min(100, max(0, $percentage)),
            'last_accessed_at' => now(),
        ]);

        // Update status based on progress
        if ($this->progress_percentage >= 100) {
            $this->markAsCompleted();
        } elseif ($this->progress_percentage > 0) {
            $this->markAsInProgress();
        }
    }

    public function markAsStarted()
    {
        if ($this->isNotStarted()) {
            $this->update([
                'status' => self::STATUS_IN_PROGRESS,
                'started_at' => now(),
                'last_accessed_at' => now(),
            ]);
        }
    }

    public function markAsInProgress()
    {
        if ($this->status !== self::STATUS_IN_PROGRESS) {
            $this->update([
                'status' => self::STATUS_IN_PROGRESS,
                'last_accessed_at' => now(),
            ]);
        }
    }

    public function markAsCompleted()
    {
        if ($this->status !== self::STATUS_COMPLETED) {
            $this->update([
                'status' => self::STATUS_COMPLETED,
                'progress_percentage' => 100,
                'completed_at' => now(),
                'last_accessed_at' => now(),
            ]);

            // Check if user has completed all onboarding courses
            $this->checkAndUpdateUserOnboardingStatus();
        }
    }

    public function updateLastAccessed()
    {
        $this->update(['last_accessed_at' => now()]);
    }

    private function checkAndUpdateUserOnboardingStatus()
    {
        $user = $this->user;
        $onboardingCategory = CourseCategory::where('slug', 'kjoacademy-onboarding')->first();
        
        if ($onboardingCategory) {
            $totalOnboardingCourses = $onboardingCategory->courses()->count();
            $completedOnboardingCourses = $user->onboardingProgress()
                ->where('status', self::STATUS_COMPLETED)
                ->count();

            // If all onboarding courses are completed, mark user as onboarding completed
            if ($completedOnboardingCourses >= $totalOnboardingCourses) {
                $user->update(['onboarding_completed_at' => now()]);
            }
        }
    }

    // Utility methods
    public function getDurationAttribute()
    {
        if ($this->completed_at && $this->started_at) {
            return $this->started_at->diffInDays($this->completed_at);
        }
        return null;
    }

    // Model events
    protected static function boot()
    {
        parent::boot();

        // Create onboarding progress when user first accesses an onboarding course
        static::creating(function ($onboardingProgress) {
            if ($onboardingProgress->isNotStarted()) {
                $onboardingProgress->markAsStarted();
            }
        });
    }
}
