<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthEndpointGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $headers = $request->headers;
        $origin = $headers->get('X-Request-Id');
        $username = $headers->get('X-Username');
        $clientId = $headers->get('X-Client-ID');

        return $next($request);
    }
}
