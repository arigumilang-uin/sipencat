<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\BarangMasukRequest;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Supplier;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BarangMasukController extends Controller
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
     * Display a listing of barang masuk
     */
    public function index(Request $request): View
    {
        Gate::authorize('canManageInventory');

        $query = BarangMasuk::with(['barang', 'supplier', 'user'])
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

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $barangMasuks = $query->paginate(15)->withQueryString();

        // For filter dropdowns
        $barangs = Barang::orderBy('nama_barang')->get();
        $suppliers = Supplier::orderBy('nama_supplier')->get();

        return view('inventory.barang-masuk.index', compact('barangMasuks', 'barangs', 'suppliers'));
    }

    /**
     * Show the form for creating a new barang masuk
     */
    public function create(): View
    {
        Gate::authorize('canManageInventory');

        $barangs = Barang::orderBy('nama_barang')->get();
        $suppliers = Supplier::orderBy('nama_supplier')->get();

        return view('inventory.barang-masuk.create', compact('barangs', 'suppliers'));
    }

    /**
     * Store a newly created barang masuk (with automatic stock update)
     */
    public function store(BarangMasukRequest $request): RedirectResponse
    {
        Gate::authorize('canManageInventory');

        try {
            // Call service to handle stock update and create transaction
            // Service will handle DB transaction and stock increment
            $barangMasuk = $this->inventoryService->addStock(
                $request->validated()
            );

            return redirect()
                ->route('inventory.barang-masuk.show', $barangMasuk)
                ->with('success', 'Barang masuk berhasil dicatat dan stok telah diperbarui.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan barang masuk: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified barang masuk
     */
    public function show(BarangMasuk $barangMasuk): View
    {
        Gate::authorize('canManageInventory');

        $barangMasuk->load(['barang', 'supplier', 'user']);

        return view('inventory.barang-masuk.show', compact('barangMasuk'));
    }

    /**
     * Show the form for editing the specified barang masuk
     */
    public function edit(BarangMasuk $barangMasuk): View
    {
        Gate::authorize('canManageInventory');

        $barangs = Barang::orderBy('nama_barang')->get();
        $suppliers = Supplier::orderBy('nama_supplier')->get();

        return view('inventory.barang-masuk.edit', compact('barangMasuk', 'barangs', 'suppliers'));
    }

    /**
     * Update the specified barang masuk in storage
     */
    public function update(BarangMasukRequest $request, BarangMasuk $barangMasuk): RedirectResponse
    {
        Gate::authorize('canManageInventory');

        try {
            // Calculate stock difference
            $oldJumlah = $barangMasuk->jumlah;
            $newJumlah = $request->jumlah;
            $difference = $newJumlah - $oldJumlah;

            // Update barang masuk record
            $barangMasuk->update($request->validated());

            // Adjust stock based on difference
            if ($difference != 0) {
                $barang = $barangMasuk->barang;
                $barang->stok += $difference;
                $barang->save();
            }

            return redirect()
                ->route('inventory.barang-masuk.show', $barangMasuk)
                ->with('success', 'Data barang masuk berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified barang masuk from storage
     */
    public function destroy(BarangMasuk $barangMasuk): RedirectResponse
    {
        Gate::authorize('isAdmin'); // Only admin can delete

        try {
            // Revert stock before deleting
            $barang = $barangMasuk->barang;
            $barang->stok -= $barangMasuk->jumlah;
            $barang->save();

            $barangMasuk->delete();

            return redirect()
                ->route('inventory.barang-masuk.index')
                ->with('success', 'Data barang masuk berhasil dihapus dan stok telah dikembalikan.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
