<?php

namespace App\Http\Services;

use App\Http\Repositories\CourseModuleRepository;
use Illuminate\Http\Request;

class CourseModuleService
{
    public function __construct(private CourseModuleRepository $courseModuleRepository) {}

    public function getCourseModules(Request $request)
    {
        $result = $this->courseModuleRepository->getMany($request->query());
        return $result;
    }

    public function getCourseModuleById(string $id)
    {
        $result = $this->courseModuleRepository->getById($id);
        return $result;
    }

    public function createCourseModule(array $data)
    {
        // If order is not provided, get the next available order
        if (!isset($data['order'])) {
            $data['order'] = $this->courseModuleRepository->getNextOrder($data['course_id']);
        }

        // Set default values
        $data['lessons_count'] = $data['lessons_count'] ?? 0;
        $data['duration_seconds'] = $data['duration_seconds'] ?? 0;
        $data['is_published'] = $data['is_published'] ?? false;

        $result = $this->courseModuleRepository->create($data);
        return $result;
    }

    public function updateCourseModule(string $id, array $data)
    {
        $result = $this->courseModuleRepository->update($id, $data);
        return $result;
    }

    public function deleteCourseModule(string $id)
    {
        $result = $this->courseModuleRepository->delete($id);
        return $result;
    }

    public function getModulesByCourseId(string $courseId)
    {
        $result = $this->courseModuleRepository->getByCourseId($courseId);
        return $result;
    }
}
