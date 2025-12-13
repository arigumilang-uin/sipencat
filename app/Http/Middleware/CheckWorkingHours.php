<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\WorkingHour;
use Illuminate\Support\Facades\Auth;

class CheckWorkingHours
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check if not authenticated
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // Admin always has access (24/7)
        if ($user->role->value === 'admin') {
            return $next($request);
        }

        // Allow access to work-ended page (goodbye page)
        if ($request->routeIs('work.ended') || $request->is('overtime/request')) {
            return $next($request);
        }

        // Check if user can access now based on working hours
        $canAccess = WorkingHour::canAccessNow($user->role->value);
        
        if (!$canAccess) {
            // Get next access time for friendly message
            $nextAccessTime = WorkingHour::getNextAccessTime($user->role->value);
            
            // Logout user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Redirect to login with message
            $dayName = ucfirst(now()->locale('id')->dayName);
            
            return redirect()->route('login')->withErrors([
                'working_hours' => sprintf(
                    'Akses sistem dibatasi berdasarkan jam kerja. Saat ini Anda berada di luar jam kerja. %s',
                    ($nextAccessTime ? "Jam kerja hari {$dayName} dimulai pukul {$nextAccessTime}." : "Silakan hubungi administrator untuk informasi jam kerja.")
                ),
            ]);
        }

        return $next($request);
    }
}
