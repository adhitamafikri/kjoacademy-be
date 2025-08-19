<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\CourseCategoryService;
use Exception;

class CourseCategoryController extends Controller
{
    public function __construct(private CourseCategoryService $courseCategoryService) {}

    public function getCategories(Request $request)
    {
        // dd($request);
        try {
            $result = $this->courseCategoryService->getCategories($request);
            return response()->json([
                "data" => $result,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function getCategoryBySlug(Request $request)
    {
        try {
            $result = $this->courseCategoryService->getCategoryBySlug($request->slug);
            if ($result === null) {
                return response()->json([
                    "message" => "Course Category not found",
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
}
