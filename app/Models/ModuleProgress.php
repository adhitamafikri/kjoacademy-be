<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleProgress extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleProgressFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'module_progress';

    protected $fillable = [
        'user_id',
        'course_module_id',
        'course_enrollment_id',
        'status',
        'progress_percentage',
        'lessons_completed_count',
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
        'lessons_completed_count' => 'integer',
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

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'course_enrollment_id');
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class, 'course_module_id', 'course_module_id')
                    ->where('user_id', $this->user_id);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByModule($query, $moduleId)
    {
        return $query->where('course_module_id', $moduleId);
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
        return $query->with(['user', 'module', 'enrollment']);
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
    public function updateProgress()
    {
        $totalLessons = $this->module->lessons()->count();
        $completedLessons = $this->lessonProgress()->where('status', LessonProgress::STATUS_COMPLETED)->count();
        
        $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
        
        $this->update([
            'progress_percentage' => $progressPercentage,
            'lessons_completed_count' => $completedLessons,
            'last_accessed_at' => now(),
        ]);

        // Update status based on progress
        if ($progressPercentage >= 100) {
            $this->markAsCompleted();
        } elseif ($progressPercentage > 0) {
            $this->markAsInProgress();
        }

        // Update enrollment progress
        $this->updateEnrollmentProgress();
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
        }
    }

    public function updateLastAccessed()
    {
        $this->update(['last_accessed_at' => now()]);
    }

    private function updateEnrollmentProgress()
    {
        // Calculate overall course progress based on all modules
        $enrollment = $this->enrollment;
        $totalModules = $enrollment->course->modules()->count();
        $completedModules = $enrollment->moduleProgress()->where('status', self::STATUS_COMPLETED)->count();
        
        $courseProgress = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;
        $enrollment->updateProgress($courseProgress);
    }

    // Utility methods
    public function getDurationAttribute()
    {
        if ($this->completed_at && $this->started_at) {
            return $this->started_at->diffInDays($this->completed_at);
        }
        return null;
    }

    public function getRemainingLessonsAttribute()
    {
        return $this->module->lessons()->count() - $this->lessons_completed_count;
    }

    // Model events
    protected static function boot()
    {
        parent::boot();

        // Create module progress when user first accesses a module
        static::creating(function ($moduleProgress) {
            if ($moduleProgress->isNotStarted()) {
                $moduleProgress->markAsStarted();
            }
        });

        // Update enrollment progress when module progress changes
        static::updated(function ($moduleProgress) {
            if ($moduleProgress->isDirty(['status', 'progress_percentage'])) {
                $moduleProgress->updateEnrollmentProgress();
            }
        });
    }
}
