<?php

namespace App\Http\Repositories;

use App\Models\Course;

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
}
