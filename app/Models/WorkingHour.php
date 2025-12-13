<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class WorkingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_id',
        'role',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Relationship: Working hour belongs to shift (optional)
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Get day name in Indonesian
     */
    public function getDayNameAttribute(): string
    {
        return match($this->day_of_week) {
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
            default => $this->day_of_week,
        };
    }

    /**
     * Check if current time is within working hours
     */
    public function isWithinWorkingHours(?Carbon $time = null): bool
    {
        $time = $time ?? now();
        
        $currentTime = $time->format('H:i:s');
        $startTime = Carbon::parse($this->start_time)->format('H:i:s');
        $endTime = Carbon::parse($this->end_time)->format('H:i:s');
        
        return $currentTime >= $startTime && $currentTime <= $endTime;
    }

    /**
     * Get working hours for a specific role and day
     */
    public static function getForRoleAndDay(string $role, string $dayOfWeek): ?WorkingHour
    {
        return static::whereNull('shift_id')
            ->where('role', $role)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Check if a role can access at current time
     * Priority: Active Extension > Shift-based > Role-based
     */
    public static function canAccessNow(string $role): bool
    {
        // Admin & Pemilik always can access (24/7)
        if (in_array($role, ['admin', 'pemilik'])) {
            return true;
        }

        $user = auth()->user();
        
        // PRIORITY 1: Check for active overtime extension
        if ($user) {
            $activeExtension = \App\Models\OvertimeRequest::where('user_id', $user->id)
                ->active()
                ->first();
            
            if ($activeExtension) {
                // User has active extension, allow access!
                return true;
            }
        }
        
        $dayOfWeek = strtolower(now()->format('l')); // 'monday', 'tuesday', etc

        // PRIORITY 2: Check shift-based working hours
        if ($user && $user->shift()->exists()) {
            $shift = $user->shift()->first();
            
            $shiftWorkingHour = static::where('shift_id', $shift->id)
                ->where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->first();
            
            if ($shiftWorkingHour) {
                // Shift rule exists, use it
                return $shiftWorkingHour->isWithinWorkingHours();
            }
            // No shift rule for this day, check role-based as fallback
        }

        // PRIORITY 3: Fallback to role-based working hours
        $roleWorkingHour = static::getForRoleAndDay($role, $dayOfWeek);
        
        // If no working hours defined, allow access (default open)
        if (!$roleWorkingHour) {
            return true;
        }
        
        return $roleWorkingHour->isWithinWorkingHours();
    }

    /**
     * Get next available access time for a role
     */
    public static function getNextAccessTime(string $role): ?string
    {
        $user = auth()->user();
        $dayOfWeek = strtolower(now()->format('l'));

        // Check shift-based first
        if ($user && $user->shift()->exists()) {
            $shift = $user->shift()->first();
            $shiftWorkingHour = static::where('shift_id', $shift->id)
                ->where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->first();
            
            if ($shiftWorkingHour) {
                return Carbon::parse($shiftWorkingHour->start_time)->format('H:i');
            }
        }

        // Fallback to role-based
        $workingHour = static::getForRoleAndDay($role, $dayOfWeek);
        
        if (!$workingHour) {
            return null;
        }
        
        return Carbon::parse($workingHour->start_time)->format('H:i');
    }
}
