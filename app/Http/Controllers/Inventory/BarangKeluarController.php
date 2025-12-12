<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\BarangKeluarRequest;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BarangKeluarController extends Controller
{
    /**
     * InventoryService instance
     */
    protected InventoryService $inventoryService;

    /**
     * Create a new controller instance.
     */
    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
        // Middleware is handled in routes/web.php for Laravel 11
    }

    /**
     * Display a listing of barang keluar
     */
    public function index(Request $request): View
    {
        Gate::authorize('canManageInventory');

        $query = BarangKeluar::with(['barang', 'user'])
            ->latest('tanggal')
            ->latest('created_at');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Filter by barang
        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $barangKeluars = $query->paginate(15)->withQueryString();

        // For filter dropdown
        $barangs = Barang::orderBy('nama_barang')->get();

        return view('inventory.barang-keluar.index', compact('barangKeluars', 'barangs'));
    }

    /**
     * Show the form for creating a new barang keluar
     */
    public function create(): View
    {
        Gate::authorize('canManageInventory');

        $barangs = Barang::where('stok', '>', 0)->orderBy('nama_barang')->get();

        return view('inventory.barang-keluar.create', compact('barangs'));
    }

    /**
     * Store a newly created barang keluar (with automatic stock reduction)
     */
    public function store(BarangKeluarRequest $request): RedirectResponse
    {
        Gate::authorize('canManageInventory');

        try {
            // Call service to handle stock reduction and create transaction
            $barangKeluar = $this->inventoryService->reduceStock(
                $request->validated()
            );

            return redirect()
                ->route('inventory.barang-keluar.show', $barangKeluar)
                ->with('success', 'Barang keluar berhasil dicatat dan stok telah dikurangi.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan barang keluar: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified barang keluar
     */
    public function show(BarangKeluar $barangKeluar): View
    {
        Gate::authorize('canManageInventory');

        $barangKeluar->load(['barang', 'user']);

        return view('inventory.barang-keluar.show', compact('barangKeluar'));
    }

    /**
     * Show the form for editing the specified barang keluar
     */
    public function edit(BarangKeluar $barangKeluar): View
    {
        Gate::authorize('canManageInventory');

        $barangs = Barang::orderBy('nama_barang')->get();

        return view('inventory.barang-keluar.edit', compact('barangKeluar', 'barangs'));
    }

    /**
     * Update the specified barang keluar
     */
    public function update(BarangKeluarRequest $request, BarangKeluar $barangKeluar): RedirectResponse
    {
        Gate::authorize('canManageInventory');

        try {
            $oldJumlah = $barangKeluar->jumlah;
            $newJumlah = $request->jumlah;
            $difference = $newJumlah - $oldJumlah;

            // Update record
            $barangKeluar->update($request->validated());

            // Adjust stock based on difference
            if ($difference != 0) {
                $barang = $barangKeluar->barang;
                
                // If increasing qty (difference > 0), reduce more stock
                // If decreasing qty (difference < 0), add stock back
                $barang->stok -= $difference;
                
                // Validate stock won't go negative
                if ($barang->stok < 0) {
                    throw new \Exception('Stok tidak mencukupi untuk perubahan ini.');
                }
                
                $barang->save();
            }

            return redirect()
                ->route('inventory.barang-keluar.show', $barangKeluar)
                ->with('success', 'Data barang keluar berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified barang keluar
     */
    public function destroy(BarangKeluar $barangKeluar): RedirectResponse
    {
        Gate::authorize('isAdmin');

        try {
            // Add stock back before deleting
            $barang = $barangKeluar->barang;
            $barang->stok += $barangKeluar->jumlah;
            $barang->save();

            $barangKeluar->delete();

            return redirect()
                ->route('inventory.barang-keluar.index')
                ->with('success', 'Data barang keluar berhasil dihapus dan stok telah dikembalikan.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
