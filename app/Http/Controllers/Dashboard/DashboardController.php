<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is handled in routes/web.php for Laravel 11
    }

    /**
     * Display dashboard based on user role
     */
    public function index(): View
    {
        $user = Auth::user();

        // Redirect to role-specific dashboard
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        if ($user->isGudang()) {
            return $this->gudangDashboard();
        }

        if ($user->isPemilik()) {
            return $this->pemilikDashboard();
        }

        // Fallback
        return view('dashboard.index', compact('user'));
    }

    /**
     * Admin Dashboard
     */
    public function adminDashboard(): View
    {
        $user = Auth::user();
        
        // Get statistics for admin
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_barang' => \App\Models\Barang::count(),
            'total_suppliers' => \App\Models\Supplier::count(),
            'low_stock_items' => \App\Models\Barang::lowStock()->count(),
            'recent_audit_logs' => \App\Models\AuditLog::latest()->take(10)->get(),
        ];

        return view('dashboard.admin', compact('user', 'stats'));
    }

    /**
     * Gudang Dashboard
     */
    public function gudangDashboard(): View
    {
        $user = Auth::user();
        
        // Get statistics for gudang
        $stats = [
            'total_barang' => \App\Models\Barang::count(),
            'low_stock_items' => \App\Models\Barang::lowStock()->count(),
            'out_of_stock_items' => \App\Models\Barang::outOfStock()->count(),
            'recent_barang_masuk' => \App\Models\BarangMasuk::with(['barang', 'supplier'])
                ->latest()
                ->take(5)
                ->get(),
            'recent_barang_keluar' => \App\Models\BarangKeluar::with('barang')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.gudang', compact('user', 'stats'));
    }

    /**
     * Pemilik Dashboard
     */
    public function pemilikDashboard(): View
    {
        $user = Auth::user();
        
        // Get statistics for pemilik (business owner focus)
        $stats = [
            'total_barang' => \App\Models\Barang::count(),
            'total_value' => \App\Models\Barang::sum(DB::raw('stok * harga')),
            'low_stock_items' => \App\Models\Barang::lowStock()->count(),
            
            // Monthly transaction counts and quantities
            'monthly_transactions' => [
                'masuk' => \App\Models\BarangMasuk::whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year)
                    ->count(),
                'keluar' => \App\Models\BarangKeluar::whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year)
                    ->count(),
            ],
            
            // Monthly quantity movements
            'monthly_in_qty' => \App\Models\BarangMasuk::whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->sum('jumlah'),
            'monthly_out_qty' => \App\Models\BarangKeluar::whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->sum('jumlah'),
            
            // Top 5 items by total value (stock * price)
            'top_items' => \App\Models\Barang::select('*')
                ->selectRaw('(stok * harga) as total_value')
                ->orderByRaw('(stok * harga) DESC')
                ->take(5)
                ->get(),
            
            // Low stock items (for alert list)
            'low_stock_list' => \App\Models\Barang::lowStock()
                ->orderBy('stok', 'asc')
                ->take(10)
                ->get(),
        ];

        return view('dashboard.pemilik', compact('user', 'stats'));
    }
}
