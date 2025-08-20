<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\CourseService;
use Exception;

class CourseController extends Controller
{
    public function __construct(private CourseService $courseService) {}

    public function getCourses(Request $request)
    {
        try {
            $result = $this->courseService->getMany($request->query());
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
            $result = $this->courseService->getCourseBySlug($request->slug);
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
            $result = $this->courseService->getCoursesByCategorySlug($request->slug, $request->query());
            return response()->json([
                "data" => $result,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }
}
