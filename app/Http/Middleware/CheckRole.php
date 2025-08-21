<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized. Please login first.',
                'status' => 'error'
            ], 401);
        }

        $user = Auth::user();
        
        // Get user's role
        $userRole = $user->role;
        
        // Check if user has a role
        if (!$userRole) {
            return response()->json([
                'message' => 'User has no assigned role.',
                'status' => 'error'
            ], 403);
        }

        // Check if user's role matches any of the required roles
        if (!in_array($userRole->name, $roles)) {
            return response()->json([
                'message' => 'Access denied. Insufficient permissions.',
                'status' => 'error'
            ], 403);
        }

        return $next($request);
    }
}
