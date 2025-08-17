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
        return $this->hasMany(Course::class, 'category_id');
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

    public function incrementCoursesCount()
    {
        $this->increment('courses_count');
    }

    public function decrementCoursesCount()
    {
        $this->decrement('courses_count');
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
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
