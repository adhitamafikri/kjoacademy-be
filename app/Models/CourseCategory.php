<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CourseCategory extends Model
{
    /** @use HasFactory<\Database\Factories\CourseCategoryFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'course_categories';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'courses_count',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationship to courses
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_course_category', 'course_category_id', 'course_id')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    // Get courses where this category is primary
    public function primaryCourses()
    {
        return $this->courses()->wherePivot('is_primary', true);
    }

    // Get courses where this category is additional
    public function additionalCourses()
    {
        return $this->courses()->wherePivot('is_primary', false);
    }

    // Use cached count for listings
    public function getCoursesCountAttribute()
    {
        return $this->courses_count ?? 0;
    }

    // Use real-time count when needed
    public function getRealTimeCoursesCountAttribute()
    {
        return $this->courses()->count();
    }

    // Method to refresh count (for admin use)
    public function refreshCoursesCount()
    {
        $this->update(['courses_count' => $this->courses()->count()]);
    }

    // Batch refresh for all categories (useful for maintenance)
    public static function refreshAllCoursesCounts()
    {
        static::chunk(100, function ($categories) {
            foreach ($categories as $category) {
                $category->refreshCoursesCount();
            }
        });
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    // Scope to order by courses count
    public function scopeOrderByCoursesCount($query, $direction = 'desc')
    {
        return $query->orderBy('courses_count', $direction);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->title);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('title') && empty($category->slug)) {
                $category->slug = Str::slug($category->title);
            }
        });
    }
}
