<?php

if (!function_exists('getUserWorkingHoursToday')) {
    /**
     * Get user's working hours for today
     */
    function getUserWorkingHoursToday($user = null)
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return null;
        }
        
        // Admin & Pemilik tidak ada restriction (24/7)
        if (in_array($user->role->value, ['admin', 'pemilik'])) {
            return [
                'has_restriction' => false,
                'message' => 'Akses penuh 24/7',
            ];
        }
        
        // PRIORITY 1: Check for active overtime extension
        $activeExtension = \App\Models\OvertimeRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('expires_at', '>', now())
            ->first();
        
        if ($activeExtension) {
            // User has active extension - show countdown to extension expiry
            return [
                'has_restriction' => true,
                'type' => 'extension',
                'is_extension' => true,
                'extension_id' => $activeExtension->id,
                'shift_name' => $user->shift()->exists() ? $user->shift->first()->name : null,
                'start_time' => now(), // Extension started now (or when approved)
                'end_time' => $activeExtension->expires_at,
                'granted_minutes' => $activeExtension->granted_minutes,
                'is_active_now' => true, // Always active if extension exists
            ];
        }
        
        $dayOfWeek = strtolower(now()->format('l'));
        
        // PRIORITY 2: Check shift-based 
        if ($user->shift()->exists()) {
            $shift = $user->shift()->first();
            $workingHour = \App\Models\WorkingHour::where('shift_id', $shift->id)
                ->where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->first();
            
            if ($workingHour) {
                return [
                    'has_restriction' => true,
                    'type' => 'shift',
                    'shift_name' => $shift->name,
                    'shift_id' => $shift->id,
                    'start_time' => $workingHour->start_time,
                    'end_time' => $workingHour->end_time,
                    'is_active_now' => $workingHour->isWithinWorkingHours(),
                ];
            }
        }
        
        // PRIORITY 3: Fallback to role-based
        $workingHour = \App\Models\WorkingHour::getForRoleAndDay($user->role->value, $dayOfWeek);
        
        if ($workingHour) {
            return [
                'has_restriction' => true,
                'type' => 'role',
                'start_time' => $workingHour->start_time,
                'end_time' => $workingHour->end_time,
                'is_active_now' => $workingHour->isWithinWorkingHours(),
            ];
        }
        
        return [
            'has_restriction' => false,
            'message' => 'Tidak ada pembatasan jam kerja hari ini',
        ];
    }
}

if (!function_exists('calculateRemainingWorkTime')) {
    /**
     * Calculate remaining work time
     */
    function calculateRemainingWorkTime($endTime)
    {
        $now = now();
        $end = \Carbon\Carbon::parse($endTime);
        
        if ($now->greaterThanOrEqualTo($end)) {
            return ['expired' => true];
        }
        
        $diff = $now->diff($end);
        
        return [
            'expired' => false,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
            'total_minutes' => ($diff->h * 60) + $diff->i,
            'total_seconds' => ($diff->h * 3600) + ($diff->i * 60) + $diff->s,
        ];
    }
}

if (!function_exists('getWorkTimePercentage')) {
    /**
     * Get work time completion percentage
     */
    function getWorkTimePercentage($startTime, $endTime)
    {
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);
        $now = now();
        
        // If before start time
        if ($now->lessThan($start)) {
            return 0;
        }
        
        // If after end time
        if ($now->greaterThan($end)) {
            return 100;
        }
        
        $totalMinutes = $start->diffInMinutes($end);
        $elapsedMinutes = $start->diffInMinutes($now);
        
        return min(100, round(($elapsedMinutes / $totalMinutes) * 100, 1));
    }
}
