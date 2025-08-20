<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use App\Http\Repositories\CourseRepository;

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
}
