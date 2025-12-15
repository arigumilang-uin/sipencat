<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Track user activity for online status
        // Check working hours untuk web requests (after session started)
        // Prevent error pages from being redirect target after login
        $middleware->web(append: [
            \App\Http\Middleware\TrackUserActivity::class,
            \App\Http\Middleware\CheckWorkingHours::class,
            \App\Http\Middleware\PreventErrorPageRedirect::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle 419 Token Mismatch (CSRF expired) - Best Practice
        $exceptions->renderable(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            // Clear the problematic intended URL from session
            $request->session()->forget('url.intended');
            
            // Determine redirect target based on authentication status
            if (auth()->check()) {
                // User is logged in - redirect to dashboard with message
                return redirect()->route('dashboard')
                    ->with('warning', 'Sesi Anda telah kedaluwarsa. Silakan coba lagi.');
            } else {
                // User not logged in - redirect to login with message
                return redirect()->route('login')
                    ->with('info', 'Sesi Anda telah kedaluwarsa. Silakan login kembali.');
            }
        });
        
        // Handle 403 Forbidden - Custom message
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                $request->session()->forget('url.intended');
            }
            // Return null to use default rendering
            return null;
        });
    })->create();
