<?php

namespace App\Http\Services;

use App\Http\Repositories\CourseCategoryRepository;
use Illuminate\Http\Request;

class CourseCategoryService
{
    public function __construct(private CourseCategoryRepository $courseCategoryRepository) {}

    public function getCategories(Request $request)
    {
        $result = $this->courseCategoryRepository->getMany($request->query());
        return $result;
    }

    public function getCategoryBySlug(string $slug)
    {
        $result = $this->courseCategoryRepository->findBySlug($slug);
        return $result;
    }
}
