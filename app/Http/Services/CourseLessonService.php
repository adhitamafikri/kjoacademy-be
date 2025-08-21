<?php

namespace App\Http\Services;

use App\Http\Repositories\CourseLessonRepository;
use Illuminate\Http\Request;

class CourseLessonService
{
    public function __construct(private CourseLessonRepository $courseLessonRepository) {}

    public function getCourseLessons(Request $request)
    {
        $result = $this->courseLessonRepository->getMany($request->query());
        return $result;
    }

    public function getCourseLessonById(string $id)
    {
        $result = $this->courseLessonRepository->getById($id);
        return $result;
    }

    public function createCourseLesson(array $data)
    {
        // If order is not provided, get the next available order
        if (!isset($data['order'])) {
            $data['order'] = $this->courseLessonRepository->getNextOrder($data['course_module_id']);
        }

        // Set default values
        $data['is_published'] = $data['is_published'] ?? false;

        $result = $this->courseLessonRepository->create($data);
        return $result;
    }

    public function updateCourseLesson(string $id, array $data)
    {
        $result = $this->courseLessonRepository->update($id, $data);
        return $result;
    }

    public function deleteCourseLesson(string $id)
    {
        $result = $this->courseLessonRepository->delete($id);
        return $result;
    }

    public function getLessonsByModuleId(string $moduleId)
    {
        $result = $this->courseLessonRepository->getByModuleId($moduleId);
        return $result;
    }

    public function getLessonsByCourseId(string $courseId)
    {
        $result = $this->courseLessonRepository->getByCourseId($courseId);
        return $result;
    }
}
