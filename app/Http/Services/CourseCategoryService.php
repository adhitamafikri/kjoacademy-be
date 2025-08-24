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
        
        // Transform the paginated data to match API contract
        $result->getCollection()->transform(function ($category) {
            return $this->transformCategoryForApi($category);
        });
        
        return $result;
    }

    public function getCategoryBySlug(string $slug)
    {
        $result = $this->courseCategoryRepository->findBySlug($slug);
        
        if ($result) {
            return $this->transformCategoryForApi($result);
        }
        
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

        $data['courses_count'] = 0;

        $result = $this->courseCategoryRepository->create($data);
        return $this->transformCategoryForApi($result);
    }

    public function updateCategory(string $slug, array $data)
    {
        // Find the category to update
        $category = $this->courseCategoryRepository->findBySlug($slug);
        if (!$category) {
            throw new Exception('Course category not found.');
        }

        // Generate slug if title is being updated and slug is not provided
        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Check if new slug already exists (excluding current category)
        if (isset($data['slug']) && $data['slug'] !== $slug) {
            $existingCategory = $this->courseCategoryRepository->findBySlug($data['slug']);
            if ($existingCategory) {
                throw new Exception('A category with this slug already exists.');
            }
        }

        $result = $this->courseCategoryRepository->update($category, $data);
        return $this->transformCategoryForApi($result);
    }

    public function deleteCategory(string $slug)
    {
        // Find the category to delete
        $category = $this->courseCategoryRepository->findBySlug($slug);
        if (!$category) {
            throw new Exception('Course category not found.');
        }

        // Check if category has courses assigned
        if ($category->courses_count > 0) {
            throw new Exception('Cannot delete category that has courses assigned. Please reassign or delete the courses first.');
        }

        $result = $this->courseCategoryRepository->delete($category);
        return $this->transformCategoryForApi($result);
    }

    /**
     * Transform category data to match API contract response shape
     */
    private function transformCategoryForApi($category)
    {
        return [
            'id' => $category->id,
            'title' => $category->title,
            'slug' => $category->slug,
            'description' => $category->description,
            'courses_count' => $category->courses_count,
            'created_at' => $category->created_at->toISOString(),
            'updated_at' => $category->updated_at->toISOString(),
        ];
    }
}
