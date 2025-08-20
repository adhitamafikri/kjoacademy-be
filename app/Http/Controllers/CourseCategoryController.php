<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\CourseCategoryService;
use App\Http\Requests\CreateCourseCategoryRequest;
use App\Http\Requests\UpdateCourseCategoryRequest;
use Exception;

class CourseCategoryController extends Controller
{
    public function __construct(private CourseCategoryService $courseCategoryService) {}

    public function getCategories(Request $request)
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

    public function createCategory(CreateCourseCategoryRequest $request)
    {
        try {
            $result = $this->courseCategoryService->createCategory($request->validated());

            return response()->json([
                "message" => "Course category created successfully",
                "data" => $result,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function updateCategory(UpdateCourseCategoryRequest $request, string $slug)
    {
        try {
            $result = $this->courseCategoryService->updateCategory($slug, $request->validated());

            return response()->json([
                "message" => "Course category updated successfully",
                "data" => $result,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 404);
        }
    }

    public function deleteCategory(Request $request) {}
}
