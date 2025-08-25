<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Services\CourseService;
use Exception;

class CourseController extends Controller
{

    public function __construct(private CourseService $courseService) {}

    public function index(Request $request)
    {
        try {
            $result = $this->courseService->getMany($request);
            return response()->json($result, 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function store(CreateCourseRequest $request)
    {
        try {
            $result = $this->courseService->createCourse($request->validated());

            return response()->json($result, 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $slug)
    {
        try {
            $result = $this->courseService->getCourseBySlug($slug);
            if ($result === null) {
                return response()->json([
                    "message" => "Course not found",
                ], 404);
            }
            return response()->json($result, 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateCourseRequest $request, string $slug)
    {
        try {
            $result = $this->courseService->updateCourse($slug, $request->validated());

            return response()->json($result, 200);
        } catch (Exception $e) {
            $statusCode = $e->getMessage() === 'Course not found.' ? 404 : 500;
            return response()->json([
                "message" => $e->getMessage(),
            ], $statusCode);
        }
    }

    public function destroy(string $slug)
    {
        try {
            $this->courseService->deleteCourse($slug);

            return response()->json([
                "message" => "Course deleted successfully",
            ], 200);
        } catch (Exception $e) {
            $statusCode = $e->getMessage() === 'Course not found.' ? 404 : 422;
            return response()->json([
                "message" => $e->getMessage(),
            ], $statusCode);
        }
    }

    public function getCoursesByCategory(Request $request, string $slug)
    {
        try {
            $result = $this->courseService->getCoursesByCategorySlug($request, $slug);
            return response()->json($result, 200);
        } catch (Exception $e) {
            $statusCode = $e->getMessage() === 'Category not found.' ? 404 : 500;
            return response()->json([
                "message" => $e->getMessage(),
            ], $statusCode);
        }
    }
}
