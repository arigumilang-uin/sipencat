<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkingHour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class WorkingHourController extends Controller
{
    /**
     * Display working hours management
     */
    public function index(): View
    {
        Gate::authorize('canManageUsers'); // Only admin

        // Get working hours grouped by role (existing)
        $workingHoursByRole = WorkingHour::whereNull('shift_id')
            ->orderBy('role')
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->get()
            ->groupBy('role');

        // Get working hours grouped by shift (new)
        $workingHoursByShift = WorkingHour::whereNotNull('shift_id')
            ->with('shift')
            ->orderBy('shift_id')
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->get()
            ->groupBy('shift_id');

        // Get active shifts with member count
        $shifts = \App\Models\Shift::withCount('members')->where('is_active', true)->get();

        $roles = ['staff_operasional', 'pemilik']; // Admin tidak perlu restriction
        $days = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
        ];

        return view('admin.working-hours.index', compact('workingHoursByRole', 'workingHoursByShift', 'shifts', 'roles', 'days'));
    }

    /**
     * Store or update working hour
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('canManageUsers');

        // Shift-based only validation
        $validated = $request->validate([
            'shift_id' => ['required', 'exists:shifts,id'],
            'day_of_week' => ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'is_active' => ['required', 'boolean'],
        ], [
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
            'shift_id.required' => 'Shift harus dipilih.',
        ]);

        // Prepare data - always shift-based
        $uniqueConstraint = [
            'shift_id' => $validated['shift_id'],
            'day_of_week' => $validated['day_of_week'],
        ];
        
        $data = [
            'shift_id' => $validated['shift_id'],
            'role' => null, // Always null for shift-based
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'is_active' => $validated['is_active'],
        ];

        WorkingHour::updateOrCreate($uniqueConstraint, $data);

        return back()->with('success', 'Jam kerja berhasil disimpan.');
    }

    /**
     * Delete working hour
     */
    public function destroy(WorkingHour $workingHour): RedirectResponse
    {
        Gate::authorize('canManageUsers');

        $workingHour->delete();

        return back()->with('success', 'Jam kerja berhasil dihapus.');
    }

    /**
     * Toggle active status
     */
    public function toggle(WorkingHour $workingHour): RedirectResponse
    {
        Gate::authorize('canManageUsers');

        $workingHour->update(['is_active' => !$workingHour->is_active]);

        $status = $workingHour->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Jam kerja berhasil {$status}.");
    }
}
