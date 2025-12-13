<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftInfoController extends Controller
{
    /**
     * Display shift information for authenticated user
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Check if user has a shift
        if (!$user->shift()->exists()) {
            return view('shift-info.index', [
                'hasShift' => false,
                'message' => 'Anda belum ditugaskan ke shift manapun. Silakan hubungi administrator.',
            ]);
        }
        
        $shift = $user->shift()->first();
        
        // Get all members in this shift
        $members = $shift->members()
            ->orderBy('name')
            ->get();
        
        // Get working hours for this shift (ordered by day)
        $workingHours = \App\Models\WorkingHour::where('shift_id', $shift->id)
            ->where('is_active', true)
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->get();
        
        // Check current status
        $currentStatus = $this->getCurrentStatus($workingHours);
        
        return view('shift-info.index', [
            'hasShift' => true,
            'shift' => $shift,
            'members' => $members,
            'workingHours' => $workingHours,
            'currentStatus' => $currentStatus,
        ]);
    }
    
    /**
     * Determine current work status
     */
    private function getCurrentStatus($workingHours)
    {
        $now = now();
        $today = strtolower($now->format('l')); // Get day name in lowercase
        
        $todayHours = $workingHours->firstWhere('day_of_week', $today);
        
        if (!$todayHours) {
            return [
                'status' => 'no_schedule',
                'message' => 'Tidak ada jadwal kerja hari ini',
                'color' => 'secondary',
            ];
        }
        
        $start = \Carbon\Carbon::parse($todayHours->start_time);
        $end = \Carbon\Carbon::parse($todayHours->end_time);
        
        if ($now->lt($start)) {
            return [
                'status' => 'before_shift',
                'message' => "Shift belum dimulai. Mulai pukul {$start->format('H:i')}",
                'color' => 'info',
            ];
        }
        
        if ($now->between($start, $end)) {
            $remaining = $now->diffInMinutes($end);
            return [
                'status' => 'active',
                'message' => "Sedang dalam jam kerja. Berakhir pukul {$end->format('H:i')} ({$remaining} menit lagi)",
                'color' => 'success',
            ];
        }
        
        return [
            'status' => 'after_shift',
            'message' => "Jam kerja sudah berakhir. Berakhir pukul {$end->format('H:i')}",
            'color' => 'danger',
        ];
    }
}
