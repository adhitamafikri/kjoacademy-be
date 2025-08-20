<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Repositories\CourseRepository;
use Exception;

class CourseService
{
    public function __construct(private CourseRepository $courseRepository) {}

    public function getMany(Request $request)
    {
        $result = $this->courseRepository->getMany($request->query());
        return $result;
    }

    public function getCourseBySlug(Request $request)
    {
        $result = $this->courseRepository->findBySlug($request->slug);
        return $result;
    }

    public function getCoursesByCategorySlug(Request $request)
    {
        $result = $this->courseRepository->getByCategorySlug($request->slug, $request->query());
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

        $result = $this->courseRepository->create($data);
        return $result;
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

        $result = $this->courseRepository->update($course, $data);
        return $result;
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
}
