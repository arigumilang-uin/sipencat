<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ShiftController extends Controller
{
    /**
     * Display shift management
     */
    public function index(): View
    {
        Gate::authorize('canManageUsers');

        $shifts = Shift::withCount('members')->get();
        $availableUsers = User::where('role', UserRole::STAFF_OPERASIONAL)
            ->where('is_active', true)
            ->whereDoesntHave('shift')
            ->get();

        return view('admin.shifts.index', compact('shifts', 'availableUsers'));
    }

    /**
     * Store new shift
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('canManageUsers');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ], [
            'name.required' => 'Nama shift wajib diisi.',
        ]);

        Shift::create($validated);

        return back()->with('success', 'Shift berhasil ditambahkan.');
    }

    /**
     * Update shift
     */
    public function update(Request $request, Shift $shift): RedirectResponse
    {
        Gate::authorize('canManageUsers');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $shift->update($validated);

        return back()->with('success', 'Shift berhasil diperbarui.');
    }

    /**
     * Delete shift
     */
    public function destroy(Shift $shift): RedirectResponse
    {
        Gate::authorize('canManageUsers');

        if ($shift->members()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus shift yang masih memiliki anggota.');
        }

        $shift->delete();

        return back()->with('success', 'Shift berhasil dihapus.');
    }

    /**
     * Add member to shift
     */
    public function addMember(Request $request, Shift $shift): RedirectResponse
    {
        Gate::authorize('canManageUsers');

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::find($validated['user_id']);

        // Check if user is Staff Operasional
        if ($user->role !== UserRole::STAFF_OPERASIONAL) {
            return back()->with('error', 'Hanya Staff Operasional yang dapat ditambahkan ke shift.');
        }

        // Check if user already in another shift
        if ($user->shift()->exists()) {
            return back()->with('error', 'User sudah terdaftar di shift lain. Hapus dari shift sebelumnya terlebih dahulu.');
        }

        $shift->addMember($user->id);

        return back()->with('success', "{$user->name} berhasil ditambahkan ke {$shift->name}.");
    }

    /**
     * Remove member from shift
     */
    public function removeMember(Shift $shift, User $user): RedirectResponse
    {
        Gate::authorize('canManageUsers');

        $shift->removeMember($user->id);

        return back()->with('success', "{$user->name} berhasil dihapus dari {$shift->name}.");
    }

    /**
     * Toggle shift status
     */
    public function toggle(Shift $shift): RedirectResponse
    {
        Gate::authorize('canManageUsers');

        $shift->update(['is_active' => !$shift->is_active]);

        $status = $shift->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Shift berhasil {$status}.");
    }
}
