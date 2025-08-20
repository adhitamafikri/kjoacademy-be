<?php

namespace App\Http\Services;

use App\Http\Repositories\CourseCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;

class CourseCategoryService
{
    public function __construct(private CourseCategoryRepository $courseCategoryRepository) {}

    public function getCategories(Request $request)
    {
        $result = $this->courseCategoryRepository->getMany($request->query());
        return $result;
    }

    public function getCategoryBySlug(string $slug)
    {
        $result = $this->courseCategoryRepository->findBySlug($slug);
        return $result;
    }

    public function createCategory(array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Check if slug already exists
        $existingCategory = $this->courseCategoryRepository->findBySlug($data['slug']);
        if ($existingCategory) {
            throw new Exception('A category with this slug already exists.');
        }

        $result = $this->courseCategoryRepository->create($data);
        return $result;
    }
}
