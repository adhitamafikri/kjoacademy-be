<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Repositories\CourseRepository;
use Exception;
use App\Models\CourseCategory; // Added this import for getCoursesByCategorySlug

class CourseService
{
    public function __construct(private CourseRepository $courseRepository) {}

    public function getMany(Request $request)
    {
        $result = $this->courseRepository->getMany($request->query());

        // Transform the paginated data to match API contract
        $result->getCollection()->transform(function ($course) {
            return $this->transformCourseForApi($course);
        });

        return $result;
    }

    public function getCourseBySlug(string $slug)
    {
        $result = $this->courseRepository->findBySlug($slug);

        if ($result) {
            return ['data' => $this->transformCourseForApi($result)];
        }

        return $result;
    }

    public function getCoursesByCategorySlug(Request $request, string $slug)
    {
        $result = $this->courseRepository->getByCategorySlug($request->query(), $slug);

        if ($result === null) {
            throw new Exception('Category not found.');
        }

        // Get the category for transformation
        $category = CourseCategory::where('slug', $slug)->first();

        if (!$category) {
            throw new Exception('Category not found.');
        }

        // Transform the paginated data to match API contract
        $result->getCollection()->transform(function ($course) use ($category) {
            return $this->transformCourseForApi($course, $category);
        });

        return $result;
    }

    public function getMyCourses(Request $request)
    {
        $result = $this->courseRepository->getMyCourses($request->user(), $request->query());
        return $result;
    }

    public function createCourse(array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Check if slug already exists
        $existingCourse = $this->courseRepository->findBySlug($data['slug']);
        if ($existingCourse) {
            throw new Exception('A course with this slug already exists.');
        }

        // Set default values for required fields
        $data['enrollment_count'] = $data['enrollment_count'] ?? 0;
        $data['duration_seconds'] = $data['duration_seconds'] ?? 0;
        $data['is_published'] = $data['is_published'] ?? false;
        $data['thumbnail_url'] = $data['thumbnail_url'] ?? '';

        // Extract category_id for relationship
        $categoryId = $data['category_id'] ?? null;
        unset($data['category_id']); // Remove from data array as it's not a course field

        $result = $this->courseRepository->create($data);

        // Attach category if provided
        if ($categoryId) {
            $result->categories()->attach($categoryId, ['is_primary' => true]);
            $result->load(['categories', 'modules']); // Reload relationships
        }

        return $this->transformCourseForApi($result);
    }

    public function updateCourse(string $slug, array $data)
    {
        // Find the course to update
        $course = $this->courseRepository->findBySlug($slug);
        if (!$course) {
            throw new Exception('Course not found.');
        }

        // Generate slug if title is being updated and slug is not provided
        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Check if new slug already exists (excluding current course)
        if (isset($data['slug']) && $data['slug'] !== $slug) {
            $existingCourse = $this->courseRepository->findBySlug($data['slug']);
            if ($existingCourse) {
                throw new Exception('A course with this slug already exists.');
            }
        }

        // Extract category_id for relationship
        $categoryId = $data['category_id'] ?? null;
        unset($data['category_id']); // Remove from data array as it's not a course field

        $result = $this->courseRepository->update($course, $data);

        // Update category relationship if provided
        if ($categoryId !== null) {
            // Remove existing primary category
            $result->categories()->updateExistingPivot($result->categories()->pluck('id'), ['is_primary' => false]);

            // Attach new primary category
            $result->categories()->attach($categoryId, ['is_primary' => true]);
            $result->load(['categories', 'modules']); // Reload relationships
        }

        return $this->transformCourseForApi($result);
    }

    public function deleteCourse(string $slug)
    {
        // Find the course to delete
        $course = $this->courseRepository->findBySlug($slug);
        if (!$course) {
            throw new Exception('Course not found.');
        }

        // Check if course has active enrollments
        if ($course->enrollment_count > 0) {
            throw new Exception('Cannot delete course that has active enrollments. Please handle the enrollments first.');
        }

        $result = $this->courseRepository->delete($course);
        return $result;
    }

    /**
     * Transform course data to match API contract response shape
     */
    private function transformCourseForApi($course, $queryCategory = null)
    {
        // If we have a query category (from getByCategorySlug), use that
        if ($queryCategory) {
            $category = $queryCategory;
        } else {
            // Get primary category - if not found, try to get the first category
            $category = $course->primaryCategory();

            // If no primary category found, get the first category from the loaded relationship
            if (!$category && $course->categories && $course->categories->count() > 0) {
                $category = $course->categories->first();
            }
        }

        return [
            'id' => $course->id,
            'title' => $course->title,
            'slug' => $course->slug,
            'description' => $course->description,
            'thumbnail_url' => $course->thumbnail_url ?? '',
            'enrollment_count' => $course->enrollment_count ?? 0,
            'duration_seconds' => $course->duration_seconds ?? 0,
            'is_published' => $course->is_published ?? false,
            'category' => $category ? [
                'id' => $category->id,
                'title' => $category->title,
                'slug' => $category->slug,
            ] : null,
            'modules_count' => $course->modules->count(),
            'created_at' => $course->created_at->toISOString(),
            'updated_at' => $course->updated_at->toISOString(),
        ];
    }
}
