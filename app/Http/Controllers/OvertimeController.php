<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OvertimeController extends Controller
{
    /**
     * Submit overtime extension request
     */
    public function request(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'requested_minutes' => 'required|integer|in:15,30,60,120',
        ], [
            'reason.required' => 'Alasan harus diisi.',
            'requested_minutes.required' => 'Durasi harus dipilih.',
        ]);

        // Check if user already has pending request
        $existingPending = OvertimeRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->exists();

        if ($existingPending) {
            return back()->withErrors([
                'error' => 'Anda masih memiliki permintaan yang belum diproses.',
            ]);
        }

        DB::transaction(function () use ($validated) {
            $overtime = OvertimeRequest::create([
                'user_id' => auth()->id(),
                'reason' => $validated['reason'],
                'requested_minutes' => $validated['requested_minutes'],
                'status' => 'pending',
            ]);

            // Notify all admins
            NotificationService::notifyAdminsAboutOvertimeRequest($overtime);
        });

        return back()->with('success', 
            'Permintaan perpanjangan waktu telah dikirim ke Administrator. Silakan tunggu persetujuan.');
    }

    /**
     * Approve overtime request
     */
    public function approve(OvertimeRequest $overtime, Request $request)
    {
        // Authorization handled by route middleware: can:isAdmin

        $validated = $request->validate([
            'granted_minutes' => 'required|integer|min:5|max:240',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($overtime, $validated) {
            $overtime->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'granted_minutes' => $validated['granted_minutes'],
                'expires_at' => now()->addMinutes((int) $validated['granted_minutes']), // Cast to int
                'admin_notes' => $validated['admin_notes'],
            ]);

            // Notify user
            NotificationService::notifyUserAboutOvertimeApproval($overtime);
        });

        return back()->with('success', 
            "Perpanjangan waktu untuk {$overtime->user->name} telah disetujui ({$validated['granted_minutes']} menit).");
    }

    /**
     * Reject overtime request
     */
    public function reject(OvertimeRequest $overtime, Request $request)
    {
        // Authorization handled by route middleware: can:isAdmin

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ], [
            'admin_notes.required' => 'Alasan penolakan harus diisi.',
        ]);

        DB::transaction(function () use ($overtime, $validated) {
            $overtime->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'admin_notes' => $validated['admin_notes'],
            ]);

            // Notify user
            NotificationService::notifyUserAboutOvertimeRejection($overtime);
        });

        return back()->with('success', 'Permintaan perpanjangan waktu telah ditolak.');
    }
}
