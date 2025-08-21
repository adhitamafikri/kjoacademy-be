<?php

namespace App\Http\Repositories;

use App\Models\CourseLesson;

const DEFAULT_PER_PAGE = 15;
class CourseLessonRepository
{
    public function __construct(private CourseLesson $model) {}

    public function getMany(array $request)
    {
        $perPage = $request['perPage'] ?? DEFAULT_PER_PAGE;
        $q = $request['q'] ?? null;

        return CourseLesson::when($q !== null, function ($query) use ($q) {
            $query->where('title', 'like', "%$q%");
        })->simplePaginate($perPage);
    }

    public function getById(string $id)
    {
        return CourseLesson::find($id);
    }

    public function create(array $data)
    {
        return CourseLesson::create($data);
    }

    public function update(string $id, array $data)
    {
        $lesson = CourseLesson::find($id);
        if ($lesson) {
            $lesson->update($data);
        }
        return $lesson;
    }

    public function delete(string $id)
    {
        $lesson = CourseLesson::find($id);
        if ($lesson) {
            $lesson->delete();
        }
        return $lesson;
    }

    public function getByModuleId(string $moduleId)
    {
        return CourseLesson::where('course_module_id', $moduleId)
            ->orderBy('order')
            ->get();
    }

    public function getNextOrder(string $moduleId)
    {
        $maxOrder = CourseLesson::where('course_module_id', $moduleId)->max('order');
        return $maxOrder ? $maxOrder + 1 : 1;
    }

    public function getByCourseId(string $courseId)
    {
        return CourseLesson::whereHas('module', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->with(['module', 'module.course'])->orderBy('order')->get();
    }
}
