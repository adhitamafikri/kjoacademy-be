<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseLesson extends Model
{
    /** @use HasFactory<\Database\Factories\CourseLessonFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'course_lessons';

    protected $fillable = [
        'course_module_id',
        'title',
        'order',
        'lesson_type',
        'lesson_content_url',
        'duration_seconds',
        'is_published',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'is_published' => 'boolean',
        'order' => 'integer',
        'duration_seconds' => 'integer',
    ];

    // Lesson types constants
    const TYPE_VIDEO = 'video';
    const TYPE_TEXT = 'text';
    const TYPE_QUIZ = 'quiz';
    const TYPE_ASSIGNMENT = 'assignment';
    const TYPE_DOWNLOAD = 'download';

    // Relationships
    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function course()
    {
        return $this->module->course();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeByModule($query, $moduleId)
    {
        return $query->where('course_module_id', $moduleId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('lesson_type', $type);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['module', 'module.course']);
    }

    // Utility methods
    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        return $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
    }

    public function getNextLesson()
    {
        return $this->module->lessons()
            ->where('order', '>', $this->order)
            ->orderBy('order')
            ->first();
    }

    public function getPreviousLesson()
    {
        return $this->module->lessons()
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();
    }

    public function isFirstLesson()
    {
        return $this->order === 1;
    }

    public function isLastLesson()
    {
        return $this->order === $this->module->lessons()->max('order');
    }

    public function isVideoLesson()
    {
        return $this->lesson_type === self::TYPE_VIDEO;
    }

    public function isTextLesson()
    {
        return $this->lesson_type === self::TYPE_TEXT;
    }

    public function isQuizLesson()
    {
        return $this->lesson_type === self::TYPE_QUIZ;
    }

    public function isAssignmentLesson()
    {
        return $this->lesson_type === self::TYPE_ASSIGNMENT;
    }

    public function isDownloadLesson()
    {
        return $this->lesson_type === self::TYPE_DOWNLOAD;
    }

    // Content methods
    public function getContentUrl()
    {
        return $this->lesson_content_url;
    }

    public function hasContent()
    {
        return !empty($this->lesson_content_url);
    }

    // Model events
    protected static function boot()
    {
        parent::boot();

        // Update module duration when lesson is created/updated/deleted
        static::created(function ($lesson) {
            $lesson->module->refreshDuration();
            $lesson->module->incrementLessonsCount();
        });

        static::updated(function ($lesson) {
            if ($lesson->isDirty('duration_seconds')) {
                $lesson->module->refreshDuration();
            }
        });

        static::deleted(function ($lesson) {
            $lesson->module->refreshDuration();
            $lesson->module->decrementLessonsCount();
        });
    }
}
