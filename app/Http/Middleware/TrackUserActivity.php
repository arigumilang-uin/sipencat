<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * Update user's last_seen_at timestamp on every request.
     * This enables accurate "online" status tracking.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Only update if last_seen_at is older than 1 minute
            // This prevents DB write on every single request (performance optimization)
            if (!$user->last_seen_at || $user->last_seen_at->lt(now()->subMinute())) {
                $user->update([
                    'last_seen_at' => now(),
                ]);
            }
        }

        return $next($request);
    }
}
