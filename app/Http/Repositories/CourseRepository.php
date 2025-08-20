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
        return Course::simplePaginate($perPage);
    }

    public function findBySlug(string $slug)
    {
        return Course::where('slug', $slug)->first();
    }

    public function getByCategorySlug(string $slug, array $query)
    {
        $perPage = $query['perPage'] ?? DEFAULT_PER_PAGE;
        $category = CourseCategory::where('slug', $slug)->first();
        return $category->courses()->simplePaginate($perPage);
    }

    public function getMyCourses(User $user, array $query)
    {
        $perPage = $query['perPage'] ?? DEFAULT_PER_PAGE;
        return $user->enrolledCourses()->simplePaginate($perPage);
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
