<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthentic
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user session have exits so this if not work
        if (!session()->has('user_id')) {
            return redirect()->route('login.form')->with('failed', 'Please login to access this page');
        }

        // Continue to next middleware/controller
        return $next($request);
    }
}
