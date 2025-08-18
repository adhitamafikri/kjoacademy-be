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
    public function categories()
    {
        return $this->belongsToMany(CourseCategory::class)
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    // Get primary category
    public function primaryCategory()
    {
        return $this->categories()->wherePivot('is_primary', true)->first();
    }

    // Get additional categories (non-primary)
    public function additionalCategories()
    {
        return $this->categories()->wherePivot('is_primary', false);
    }

    // Set primary category
    public function setPrimaryCategory(CourseCategory $category)
    {
        // Remove existing primary
        $this->categories()->updateExistingPivot($this->categories()->pluck('id'), ['is_primary' => false]);

        // Set new primary
        $this->categories()->updateExistingPivot($category->id, ['is_primary' => true]);
    }

    // High-performance scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['categories']);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('course_categories.id', $categoryId);
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

        static::created(function ($course) {
            // Refresh counts for all categories this course belongs to
            $course->categories()->each(function ($category) {
                $category->refreshCoursesCount();
            });
        });

        static::deleted(function ($course) {
            // Refresh counts for all categories this course belonged to
            $course->categories()->each(function ($category) {
                $category->refreshCoursesCount();
            });
        });

        static::updating(function ($course) {
            if ($course->isDirty('title') && empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }
}
