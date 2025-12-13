<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Reports dashboard/index
     */
    public function index(): View
    {
        Gate::authorize('canViewReports');

        // Quick stats
        $stats = [
            'total_barang' => Barang::count(),
            'total_suppliers' => Supplier::count(),
            'total_barang_masuk' => BarangMasuk::count(),
            'total_barang_keluar' => BarangKeluar::count(),
        ];

        return view('reports.index', compact('stats'));
    }

    /**
     * Laporan Stok Barang
     */
    public function stock(Request $request): View
    {
        Gate::authorize('canViewReports');

        $query = Barang::query();

        // Filter by stock status
        if ($request->filled('status')) {
            if ($request->status === 'low') {
                $query->lowStock();
            } elseif ($request->status === 'out') {
                $query->outOfStock();
            } elseif ($request->status === 'normal') {
                $query->whereColumn('stok', '>=', 'min_stok')->where('stok', '>', 0);
            }
        }

        $barangs = $query->orderBy('nama_barang')->paginate(20);

        // Summary
        $summary = [
            'total_items' => Barang::count(),
            'low_stock' => Barang::lowStock()->count(),
            'out_of_stock' => Barang::outOfStock()->count(),
            'normal_stock' => Barang::whereColumn('stok', '>=', 'min_stok')->where('stok', '>', 0)->count(),
            'total_stock_value' => Barang::sum(DB::raw('stok * harga')),
        ];

        return view('reports.stock', compact('barangs', 'summary'));
    }

    /**
     * Laporan Transaksi Barang Masuk
     */
    public function barangMasuk(Request $request): View
    {
        Gate::authorize('canViewReports');

        $query = BarangMasuk::with(['barang', 'supplier', 'user'])
            ->latest('tanggal');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $transactions = $query->paginate(20)->withQueryString();

        // Summary
        $summaryQuery = BarangMasuk::query();
        if ($request->filled('start_date')) {
            $summaryQuery->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $summaryQuery->whereDate('tanggal', '<=', $request->end_date);
        }

        $summary = [
            'total_transactions' => $summaryQuery->count(),
            'total_quantity' => $summaryQuery->sum('jumlah'),
        ];

        $suppliers = Supplier::orderBy('nama_supplier')->get();

        return view('reports.barang-masuk', compact('transactions', 'summary', 'suppliers'));
    }

    /**
     * Laporan Transaksi Barang Keluar
     */
    public function barangKeluar(Request $request): View
    {
        Gate::authorize('canViewReports');

        $query = BarangKeluar::with(['barang', 'user'])
            ->latest('tanggal');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $transactions = $query->paginate(20)->withQueryString();

        // Summary
        $summaryQuery = BarangKeluar::query();
        if ($request->filled('start_date')) {
            $summaryQuery->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $summaryQuery->whereDate('tanggal', '<=', $request->end_date);
        }

        $summary = [
            'total_transactions' => $summaryQuery->count(),
            'total_quantity' => $summaryQuery->sum('jumlah'),
        ];

        return view('reports.barang-keluar', compact('transactions', 'summary'));
    }

    /**
     * Laporan per Supplier
     */
    public function supplier(Request $request): View
    {
        Gate::authorize('canViewReports');

        $query = Supplier::withCount('barangMasuk')
            ->withSum('barangMasuk', 'jumlah');

        $suppliers = $query->orderBy('nama_supplier')->paginate(20);

        return view('reports.supplier', compact('suppliers'));
    }

    /**
     * Laporan Mutasi Stok (Stock Movement)
     */
    public function mutation(Request $request): View
    {
        Gate::authorize('canViewReports');

        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get barang with their movement
        $barangs = Barang::with([
            'barangMasuk' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            },
            'barangKeluar' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            }
        ])->get()->map(function($barang) {
            $masuk = $barang->barangMasuk->sum('jumlah');
            $keluar = $barang->barangKeluar->sum('jumlah');
            
            return [
                'kode' => $barang->kode_barang,
                'nama' => $barang->nama_barang,
                'stok_akhir' => $barang->stok,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'mutasi' => $masuk - $keluar,
                'stok_awal' => $barang->stok - ($masuk - $keluar),
            ];
        });

        return view('reports.mutation', compact('barangs', 'startDate', 'endDate'));
    }
}
