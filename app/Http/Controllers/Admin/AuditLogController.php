<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is handled in routes/web.php for Laravel 11
    }

    /**
     * Display a listing of audit logs
     */
    public function index(Request $request): View
    {
        Gate::authorize('canViewAuditLogs');

        $query = AuditLog::with('user')->latest('created_at');

        // Filter by table name
        if ($request->filled('table_name')) {
            $query->forTable($request->input('table_name'));
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->forAction($request->input('action'));
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->byUser($request->input('user_id'));
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byDateRange(
                $request->input('start_date'),
                $request->input('end_date')
            );
        }

        $auditLogs = $query->paginate(20);

        // Get unique table names for filter dropdown
        $tables = AuditLog::select('table_name')
            ->distinct()
            ->pluck('table_name');

        return view('admin.audit-logs.index', compact('auditLogs', 'tables'));
    }

    /**
     * Display the specified audit log
     */
    public function show(AuditLog $auditLog): View
    {
        Gate::authorize('canViewAuditLogs');

        $auditLog->load('user');

        return view('admin.audit-logs.show', compact('auditLog'));
    }
}
