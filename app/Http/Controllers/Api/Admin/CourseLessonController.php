<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\CourseLessonService;
use App\Http\Requests\CreateCourseLessonRequest;
use App\Http\Requests\UpdateCourseLessonRequest;
use Exception;

class CourseLessonController extends Controller
{
    public function __construct(private CourseLessonService $courseLessonService) {}

    public function index(Request $request)
    {
        try {
            $result = $this->courseLessonService->getCourseLessons($request);
            return response()->json([
                "data" => $result,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function store(CreateCourseLessonRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $result = $this->courseLessonService->createCourseLesson($validatedData);
            
            return response()->json([
                "message" => "Lesson created successfully",
                "data" => $result
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $result = $this->courseLessonService->getCourseLessonById($id);
            if (!$result) {
                return response()->json([
                    "message" => 'Lesson not found'
                ], 404);
            }
            return response()->json([
                "data" => $result
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateCourseLessonRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();
            $result = $this->courseLessonService->updateCourseLesson($id, $validatedData);
            
            if (!$result) {
                return response()->json([
                    "message" => "Lesson not found"
                ], 404);
            }
            
            return response()->json([
                "message" => "Lesson updated successfully",
                "data" => $result
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->courseLessonService->deleteCourseLesson($id);
            
            if (!$result) {
                return response()->json([
                    "message" => "Lesson not found"
                ], 404);
            }
            
            return response()->json([
                "message" => "Lesson deleted successfully"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }
}
