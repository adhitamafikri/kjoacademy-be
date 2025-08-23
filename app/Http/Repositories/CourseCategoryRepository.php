<?php

namespace App\Http\Repositories;

use App\Models\CourseCategory;

const DEFAULT_PER_PAGE = 15;

class CourseCategoryRepository
{
    public function getMany(array $query)
    {
        $perPage = $query['perPage'] ?? DEFAULT_PER_PAGE;
        $q = $request['q'] ?? null;

        return CourseCategory::when($q !== null, function ($query) use ($q) {
            $query->where('title', 'like', "%$q%");
        })->paginate($perPage);
    }

    public function findBySlug(string $slug)
    {
        return CourseCategory::where('slug', $slug)->first();
    }

    public function create(array $data)
    {
        return CourseCategory::create($data);
    }

    public function update(CourseCategory $category, array $data)
    {
        $category->update($data);
        return $category->fresh();
    }

    public function delete(CourseCategory $category)
    {
        $category->delete();
        return $category;
    }
}
