<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'courses';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'thumbnail_url',
        'enrollment_count',
        'duration_seconds',
        'is_published',
        'metadata',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'is_published' => 'boolean',
        'duration_seconds' => 'integer',
        'metadata' => 'array',
    ];

    // Relationships with eager loading optimization
    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    // High-performance scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['category']);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Performance-optimized methods
    public function incrementEnrollmentCount()
    {
        $this->increment('enrollment_count');
    }

    public function decrementEnrollmentCount()
    {
        $this->decrement('enrollment_count');
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration_seconds / 3600);
        $minutes = $this->duration_seconds % 60;
        return $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });

        // Increment count when course is created
        static::created(function ($course) {
            $course->category->incrementCoursesCount();
        });

        // Decrement count when course is deleted
        static::deleted(function ($course) {
            $course->category->decrementCoursesCount();
        });

        static::updating(function ($course) {
            if ($course->isDirty('title') && empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });

        // Handle category changes
        static::updated(function ($course) {
            if ($course->isDirty('category_id')) {
                // Decrement old category
                if ($course->getOriginal('category_id')) {
                    $course->getOriginal('category')->decrementCoursesCount();
                }
                // Increment new category
                $course->category->incrementCoursesCount();
            }
        });
    }
}
