<?php

namespace App\Http\Repositories;

use App\Models\CourseCategory;

const DEFAULT_PER_PAGE = 15;

class CourseCategoryRepository
{
    public function getMany(array $query)
    {
        $perPage = $query['perPage'] ?? DEFAULT_PER_PAGE;
        return CourseCategory::simplePaginate($perPage);
    }

    public function findBySlug(string $slug)
    {
        return CourseCategory::where('slug', $slug)->first();
    }
}
