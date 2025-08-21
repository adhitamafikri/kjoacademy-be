<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\CourseCategoryService;
use Exception;

class CourseCategoryController extends Controller
{
    public function __construct(private CourseCategoryService $courseCategoryService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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

    public function show(string $slug)
    {
        try {
            $result = $this->courseCategoryService->getCategoryBySlug($slug);
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
