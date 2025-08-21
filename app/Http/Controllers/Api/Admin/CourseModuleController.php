<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\CourseModuleService;
use App\Http\Requests\CreateCourseModuleRequest;
use App\Http\Requests\UpdateCourseModuleRequest;
use Exception;

class CourseModuleController extends Controller
{
    public function __construct(private CourseModuleService $courseModuleService) {}

    public function index(Request $request)
    {
        try {
            $result = $this->courseModuleService->getCourseModules($request);
            return response()->json([
                "data" => $result,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function store(CreateCourseModuleRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $result = $this->courseModuleService->createCourseModule($validatedData);
            
            return response()->json([
                "message" => "Module created successfully",
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
            $result = $this->courseModuleService->getCourseModuleById($id);
            if (!$result) {
                return response()->json([
                    "message" => 'Module not found'
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

    public function update(UpdateCourseModuleRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();
            $result = $this->courseModuleService->updateCourseModule($id, $validatedData);
            
            if (!$result) {
                return response()->json([
                    "message" => "Module not found"
                ], 404);
            }
            
            return response()->json([
                "message" => "Module updated successfully",
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
            $result = $this->courseModuleService->deleteCourseModule($id);
            
            if (!$result) {
                return response()->json([
                    "message" => "Module not found"
                ], 404);
            }
            
            return response()->json([
                "message" => "Module deleted successfully"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 500);
        }
    }
}
