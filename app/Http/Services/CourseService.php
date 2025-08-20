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
}
