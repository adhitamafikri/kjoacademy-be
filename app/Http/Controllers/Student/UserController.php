<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {}

    public function getMe(Request $request)
    {
        $user = $request->user();
        return response()->json([
            "data" => $user,
        ], 200);
    }
}
