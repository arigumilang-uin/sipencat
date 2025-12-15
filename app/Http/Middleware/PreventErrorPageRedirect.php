<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventErrorPageRedirect
{
    /**
     * Handle an incoming request.
     *
     * Prevent error pages from being saved as intended URL
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // If response is an error page (4xx or 5xx)
        $statusCode = $response->getStatusCode();
        
        if ($statusCode >= 400 && $statusCode < 600) {
            // Clear the intended URL from session
            // This prevents redirect loop after login
            $request->session()->forget('url.intended');
        }
        
        return $response;
    }
}
