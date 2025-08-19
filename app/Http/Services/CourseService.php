<?php

namespace App\Http\Services;

use App\Http\Repositories\CourseRepository;

class CourseService
{
    public function __construct(private CourseRepository $courseRepository) {}

    public function getMany(array $query)
    {
        $result = $this->courseRepository->getMany($query);
        return $result;
    }

    public function getCourseBySlug(string $slug)
    {
        $result = $this->courseRepository->findBySlug($slug);
        return $result;
    }
}
