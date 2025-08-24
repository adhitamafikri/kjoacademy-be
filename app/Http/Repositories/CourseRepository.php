<?php

namespace App\Http\Repositories;

use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\User;

const DEFAULT_PER_PAGE = 15;

class CourseRepository
{
    public function getMany(array $query)
    {
        $perPage = $query['perPage'] ?? DEFAULT_PER_PAGE;
        $q = $query['q'] ?? null;

        return Course::when($q !== null, function ($query) use ($q) {
            $query->where('title', 'like', "%$q%");
        })->paginate($perPage);
    }

    public function findBySlug(string $slug)
    {
        return Course::where('slug', $slug)->first();
    }

    public function getByCategorySlug(array $query, string $slug)
    {
        $perPage = $query['perPage'] ?? DEFAULT_PER_PAGE;
        $category = CourseCategory::where('slug', $slug)->first();
        return $category->courses()->paginate($perPage);
    }

    public function getMyCourses(User $user, array $query)
    {
        $perPage = $query['perPage'] ?? DEFAULT_PER_PAGE;
        return $user->enrolledCourses()->paginate($perPage);
    }

    public function create(array $data)
    {
        return Course::create($data);
    }

    public function update(Course $course, array $data)
    {
        $course->update($data);
        return $course->fresh();
    }

    public function delete(Course $course)
    {
        $course->delete();
        return $course;
    }
}
