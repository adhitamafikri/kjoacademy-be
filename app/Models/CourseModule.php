<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseModule extends Model
{
    /** @use HasFactory<\Database\Factories\CourseModuleFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'course_modules';

    protected $fillable = [
        'course_id',
        'title',
        'order',
        'lessons_count',
        'duration_seconds',
        'is_published',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'is_published' => 'boolean',
        'order' => 'integer',
        'lessons_count' => 'integer',
        'duration_seconds' => 'integer',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function lessons()
    {
        return $this->hasMany(CourseLesson::class, 'course_module_id')->orderBy('order');
    }

    public function publishedLessons()
    {
        return $this->hasMany(CourseLesson::class, 'course_module_id')
                    ->where('is_published', true)
                    ->orderBy('order');
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

    public function scopeWithRelations($query)
    {
        return $query->with(['course', 'lessons']);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    // Performance methods
    public function refreshLessonsCount()
    {
        $this->update(['lessons_count' => $this->lessons()->count()]);
    }

    public function incrementLessonsCount()
    {
        $this->increment('lessons_count');
    }

    public function decrementLessonsCount()
    {
        $this->decrement('lessons_count');
    }

    public function refreshDuration()
    {
        $duration = $this->lessons()->sum('duration_seconds');
        $this->update(['duration_seconds' => $duration]);
    }

    // Utility methods
    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        return $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
    }

    public function getNextModule()
    {
        return $this->course->modules()
            ->where('order', '>', $this->order)
            ->orderBy('order')
            ->first();
    }

    public function getPreviousModule()
    {
        return $this->course->modules()
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();
    }

    public function isFirstModule()
    {
        return $this->order === 1;
    }

    public function isLastModule()
    {
        return $this->order === $this->course->modules()->max('order');
    }

    // Model events
    protected static function boot()
    {
        parent::boot();

        // Update course duration when module is created/updated/deleted
        static::created(function ($module) {
            $module->course->refreshDuration();
        });

        static::updated(function ($module) {
            if ($module->isDirty('duration_seconds')) {
                $module->course->refreshDuration();
            }
        });

        static::deleted(function ($module) {
            $module->course->refreshDuration();
        });
    }
}
