<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonProgress extends Model
{
    /** @use HasFactory<\Database\Factories\LessonProgressFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'lesson_progress';

    protected $fillable = [
        'user_id',
        'course_lesson_id',
        'course_enrollment_id',
        'status',
        'started_at',
        'completed_at',
        'time_spent_seconds',
        'video_progress_seconds',
        'last_accessed_at',
        'attempts_count',
        'score',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'time_spent_seconds' => 'integer',
        'video_progress_seconds' => 'integer',
        'attempts_count' => 'integer',
        'score' => 'decimal:2',
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

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class, 'course_lesson_id');
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'course_enrollment_id');
    }

    public function module()
    {
        return $this->lesson->module();
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByLesson($query, $lessonId)
    {
        return $query->where('course_lesson_id', $lessonId);
    }

    public function scopeByEnrollment($query, $enrollmentId)
    {
        return $query->where('course_enrollment_id', $enrollmentId);
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
        return $query->with(['user', 'lesson', 'enrollment']);
    }

    public function scopeVideoLessons($query)
    {
        return $query->whereHas('lesson', function ($q) {
            $q->where('lesson_type', CourseLesson::TYPE_VIDEO);
        });
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
    public function updateVideoProgress($seconds)
    {
        $this->update([
            'video_progress_seconds' => max(0, $seconds),
            'last_accessed_at' => now(),
        ]);

        // Auto-complete if video is 90%+ watched
        if ($this->lesson->isVideoLesson() && $this->lesson->duration_seconds > 0) {
            $progressPercentage = ($this->video_progress_seconds / $this->lesson->duration_seconds) * 100;
            if ($progressPercentage >= 90) {
                $this->markAsCompleted();
            } elseif ($progressPercentage > 0) {
                $this->markAsInProgress();
            }
        }
    }

    public function updateTimeSpent($additionalSeconds)
    {
        $this->update([
            'time_spent_seconds' => $this->time_spent_seconds + $additionalSeconds,
            'last_accessed_at' => now(),
        ]);
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
                'completed_at' => now(),
                'last_accessed_at' => now(),
            ]);

            // Update module progress
            $this->updateModuleProgress();
        }
    }

    public function incrementAttempts()
    {
        $this->update([
            'attempts_count' => $this->attempts_count + 1,
            'last_accessed_at' => now(),
        ]);
    }

    public function updateScore($score)
    {
        $this->update([
            'score' => $score,
            'last_accessed_at' => now(),
        ]);
    }

    public function updateLastAccessed()
    {
        $this->update(['last_accessed_at' => now()]);
    }

    private function updateModuleProgress()
    {
        // Find or create module progress for this user and module
        $moduleProgress = ModuleProgress::firstOrCreate([
            'user_id' => $this->user_id,
            'course_module_id' => $this->lesson->course_module_id,
            'course_enrollment_id' => $this->course_enrollment_id,
        ]);

        // Update module progress
        $moduleProgress->updateProgress();
    }

    // Video resume functionality
    public function getVideoResumePosition()
    {
        return $this->video_progress_seconds;
    }

    public function canResumeVideo()
    {
        return $this->lesson->isVideoLesson() && $this->video_progress_seconds > 0;
    }

    // Utility methods
    public function getDurationAttribute()
    {
        if ($this->completed_at && $this->started_at) {
            return $this->started_at->diffInSeconds($this->completed_at);
        }
        return null;
    }

    public function getFormattedTimeSpentAttribute()
    {
        $hours = floor($this->time_spent_seconds / 3600);
        $minutes = floor(($this->time_spent_seconds % 3600) / 60);
        return $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
    }

    public function getFormattedVideoProgressAttribute()
    {
        $hours = floor($this->video_progress_seconds / 3600);
        $minutes = floor(($this->video_progress_seconds % 3600) / 60);
        $seconds = $this->video_progress_seconds % 60;
        return $hours > 0 ? "{$hours}:{$minutes}:{$seconds}" : "{$minutes}:{$seconds}";
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->lesson->isVideoLesson() && $this->lesson->duration_seconds > 0) {
            return min(100, round(($this->video_progress_seconds / $this->lesson->duration_seconds) * 100));
        }
        return $this->isCompleted() ? 100 : 0;
    }

    // Model events
    protected static function boot()
    {
        parent::boot();

        // Create lesson progress when user first accesses a lesson
        static::creating(function ($lessonProgress) {
            if ($lessonProgress->isNotStarted()) {
                $lessonProgress->markAsStarted();
            }
        });

        // Update module progress when lesson progress changes
        static::updated(function ($lessonProgress) {
            if ($lessonProgress->isDirty('status')) {
                $lessonProgress->updateModuleProgress();
            }
        });
    }
}
