<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\CourseCategoryService;
use App\Http\Requests\CreateCourseCategoryRequest;
use App\Http\Requests\UpdateCourseCategoryRequest;
use Exception;

class CourseCategoryController extends Controller
{
    public function __construct(private CourseCategoryService $courseCategoryService) {}

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

    public function store(CreateCourseCategoryRequest $request)
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

    public function update(UpdateCourseCategoryRequest $request, string $slug)
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

    public function destroy(string $slug)
    {
        try {
            $result = $this->courseCategoryService->deleteCategory($slug);
            return response()->json([
                "message" => "Course category deleted successfully",
                "data" => $result,
            ], 200);
        } catch (Exception $e) {
            $statusCode = $e->getMessage() === 'Course category not found.' ? 404 : 422;
            return response()->json([
                "message" => $e->getMessage(),
            ], $statusCode);
        }
    }
}
