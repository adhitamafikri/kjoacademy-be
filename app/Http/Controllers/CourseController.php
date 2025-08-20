<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Services\CourseService;
use Exception;

class CourseController extends Controller
{
    public function __construct(private CourseService $courseService) {}

    public function getCourses(Request $request)
    {
        try {
            $result = $this->courseService->getMany($request);
            return response()->json([
                "data" => $result,
            ], 200);;
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function getCourseBySlug(Request $request)
    {
        try {
            $result = $this->courseService->getCourseBySlug($request);
            if ($result === null) {
                return response()->json([
                    "message" => "Course not found",
                ], 404);
            }
            return response()->json([
                "data" => $result,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function getCoursesByCategory(Request $request)
    {
        try {
            $result = $this->courseService->getCoursesByCategorySlug($request);
            return response()->json([
                "data" => $result,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function getMyCourses(Request $request)
    {
        try {
            $result = $this->courseService->getMyCourses($request);
            if ($result === null) {
                return response()->json([
                    "message" => "You have not enrolled in any course",
                ], 404);
            }
            return response()->json([
                "data" => $result,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function createCourse(CreateCourseRequest $request)
    {
        try {
            $result = $this->courseService->createCourse($request->validated());

            return response()->json([
                "message" => "Course created successfully",
                "data" => $result,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function updateCourse(UpdateCourseRequest $request, string $slug)
    {
        try {
            $result = $this->courseService->updateCourse($slug, $request->validated());

            return response()->json([
                "message" => "Course updated successfully",
                "data" => $result,
            ], 200);
        } catch (Exception $e) {
            $statusCode = $e->getMessage() === 'Course not found.' ? 404 : 500;
            return response()->json([
                "message" => $e->getMessage(),
            ], $statusCode);
        }
    }

    public function deleteCourse(Request $request, string $slug)
    {
        try {
            $result = $this->courseService->deleteCourse($slug);

            return response()->json([
                "message" => "Course deleted successfully",
                "data" => $result,
            ], 200);
        } catch (Exception $e) {
            $statusCode = $e->getMessage() === 'Course not found.' ? 404 : 422;
            return response()->json([
                "message" => $e->getMessage(),
            ], $statusCode);
        }
    }
}
