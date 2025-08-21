<?php

namespace App\Http\Repositories;

use App\Models\CourseModule;

const DEFAULT_PER_PAGE = 15;
class CourseModuleRepository
{
    public function __construct(private CourseModule $model) {}

    public function getMany(array $query)
    {
        $perPage = $query['perPage'] ?? DEFAULT_PER_PAGE;
        $q = $query['q'] ?? null;

        return CourseModule::when($q !== null, function ($query) use ($q) {
            $query->where('title', 'like', "%$q%");
        })->simplePaginate($perPage);
    }

    public function getById(string $id) {
        return CourseModule::find($id);
    }

    public function create(array $data)
    {
        return CourseModule::create($data);
    }

    public function update(string $id, array $data)
    {
        $module = CourseModule::find($id);
        if ($module) {
            $module->update($data);
        }
        return $module;
    }

    public function delete(string $id)
    {
        $module = CourseModule::find($id);
        if ($module) {
            $module->delete();
        }
        return $module;
    }

    public function getByCourseId(string $courseId)
    {
        return CourseModule::where('course_id', $courseId)
            ->orderBy('order')
            ->get();
    }

    public function getNextOrder(string $courseId)
    {
        $maxOrder = CourseModule::where('course_id', $courseId)->max('order');
        return $maxOrder ? $maxOrder + 1 : 1;
    }
}
