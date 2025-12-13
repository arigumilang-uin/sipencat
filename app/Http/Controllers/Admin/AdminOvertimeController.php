<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OvertimeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminOvertimeController extends Controller
{
    /**
     * Display overtime requests management
     */
    public function index(Request $request)
    {
        Gate::authorize('canManageUsers');

        $query = OvertimeRequest::with(['user', 'approver'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Separate pending and completed
        $pendingRequests = OvertimeRequest::with(['user'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->get();

        $completedRequests = OvertimeRequest::with(['user', 'approver'])
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Active extensions count
        $activeExtensions = OvertimeRequest::active()->count();

        return view('admin.overtime.index', compact(
            'pendingRequests',
            'completedRequests',
            'activeExtensions',
            'status'
        ));
    }
}
