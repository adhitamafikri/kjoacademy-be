<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    /** @use HasFactory<\Database\Factories\EnrollmentFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'enrollments';

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'progress_percentage',
        'enrolled_at',
        'completed_at',
        'last_accessed_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'progress_percentage' => 'integer',
    ];

    // Status constants
    const STATUS_ENROLLED = 'enrolled';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DROPPED = 'dropped';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_DROPPED]);
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
        return $query->with(['user', 'course']);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('enrolled_at', '>=', now()->subDays($days));
    }

    // Status checking methods
    public function isEnrolled()
    {
        return $this->status === self::STATUS_ENROLLED;
    }

    public function isInProgress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isDropped()
    {
        return $this->status === self::STATUS_DROPPED;
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

    public function markAsDropped()
    {
        $this->update([
            'status' => self::STATUS_DROPPED,
            'last_accessed_at' => now(),
        ]);
    }

    public function updateLastAccessed()
    {
        $this->update(['last_accessed_at' => now()]);
    }

    // Utility methods
    public function getDurationAttribute()
    {
        if ($this->completed_at && $this->enrolled_at) {
            return $this->enrolled_at->diffInDays($this->completed_at);
        }
        return null;
    }

    public function getIsActiveAttribute()
    {
        return !in_array($this->status, [self::STATUS_DROPPED]);
    }

    // Model events
    protected static function boot()
    {
        parent::boot();

        // Increment course enrollment count when enrollment is created
        static::created(function ($enrollment) {
            $enrollment->course->incrementEnrollmentCount();
        });

        // Decrement course enrollment count when enrollment is deleted
        static::deleted(function ($enrollment) {
            $enrollment->course->decrementEnrollmentCount();
        });

        // Handle status changes
        static::updated(function ($enrollment) {
            if ($enrollment->isDirty('status')) {
                // Additional logic for status changes can be added here
                // For example, sending notifications, updating analytics, etc.
            }
        });
    }
}
