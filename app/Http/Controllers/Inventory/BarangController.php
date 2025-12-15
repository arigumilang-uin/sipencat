<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BarangController extends Controller
{
    /**
     * Display a listing of barang
     */
    public function index(): View
    {
        Gate::authorize('canManageInventory');

        $barangs = Barang::latest()->paginate(15);

        return view('inventory.barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new barang
     */
    public function create(): View
    {
        Gate::authorize('canManageInventory');

        return view('inventory.barang.create');
    }

    /**
     * Store a newly created barang
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('canManageInventory');

        $validated = $request->validate([
            'kode_barang' => ['required', 'string', 'max:50', 'unique:barang'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'numeric', 'min:0'],
            'min_stok' => ['required', 'integer', 'min:0'],
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi',
            'kode_barang.unique' => 'Kode barang sudah digunakan',
            'nama_barang.required' => 'Nama barang wajib diisi',
            'stok.required' => 'Stok wajib diisi',
            'stok.min' => 'Stok minimal 0',
            'harga.required' => 'Harga wajib diisi',
            'harga.min' => 'Harga minimal 0',
            'min_stok.required' => 'Minimum stok wajib diisi',
            'min_stok.min' => 'Minimum stok minimal 0',
        ]);

        Barang::create($validated);

        return redirect()
            ->route('inventory.barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Display the specified barang
     */
    public function show(Barang $barang): View
    {
        Gate::authorize('canManageInventory');

        return view('inventory.barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified barang
     */
    public function edit(Barang $barang): View
    {
        Gate::authorize('canManageInventory');

        return view('inventory.barang.edit', compact('barang'));
    }

    /**
     * Update the specified barang
     */
    public function update(Request $request, Barang $barang): RedirectResponse
    {
        Gate::authorize('canManageInventory');

        $validated = $request->validate([
            'kode_barang' => ['required', 'string', 'max:50', 'unique:barang,kode_barang,' . $barang->id],
            'nama_barang' => ['required', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'numeric', 'min:0'],
            'min_stok' => ['required', 'integer', 'min:0'],
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi',
            'kode_barang.unique' => 'Kode barang sudah digunakan',
            'nama_barang.required' => 'Nama barang wajib diisi',
            'stok.required' => 'Stok wajib diisi',
            'stok.min' => 'Stok minimal 0',
            'harga.required' => 'Harga wajib diisi',
            'harga.min' => 'Harga minimal 0',
            'min_stok.required' => 'Minimum stok wajib diisi',
            'min_stok.min' => 'Minimum stok minimal 0',
        ]);

        $barang->update($validated);

        return redirect()
            ->route('inventory.barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Remove the specified barang
     */
    public function destroy(Barang $barang): RedirectResponse
    {
        Gate::authorize('isAdmin'); // Only admin can delete

        // Check if barang has transactions
        if ($barang->barangMasuk()->count() > 0 || $barang->barangKeluar()->count() > 0) {
            return back()->with('error', 'Barang tidak dapat dihapus karena memiliki riwayat transaksi.');
        }

        $barang->delete();

        return redirect()
            ->route('inventory.barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Display transaction history for a specific barang
     */
    public function transactions(Barang $barang): View
    {
        Gate::authorize('canManageInventory');

        // Get all transactions (masuk & keluar) and merge them
        $barangMasuk = $barang->barangMasuk()
            ->with(['supplier', 'user'])
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'masuk',
                    'tanggal' => $item->tanggal,
                    'jumlah' => $item->jumlah,
                    'keterangan' => $item->keterangan,
                    'partner' => $item->supplier->nama_supplier ?? '-',
                    'partner_detail' => $item->supplier->telp ?? '-',
                    'user' => $item->user->name ?? '-',
                    'created_at' => $item->created_at,
                    'model' => $item,
                ];
            });

        $barangKeluar = $barang->barangKeluar()
            ->with(['user'])
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'keluar',
                    'tanggal' => $item->tanggal,
                    'jumlah' => $item->jumlah,
                    'keterangan' => $item->keterangan ?? '-',
                    'partner' => $item->tujuan,
                    'partner_detail' => '-',
                    'user' => $item->user->name ?? '-',
                    'created_at' => $item->created_at,
                    'model' => $item,
                ];
            });

        // Merge and sort by date (descending)
        $transactions = $barangMasuk->concat($barangKeluar)
            ->sortByDesc('tanggal')
            ->values();

        // Calculate running stock balance
        $currentStock = $barang->stok;
        $transactions = $transactions->reverse()->map(function ($transaction, $index) use (&$currentStock) {
            if ($transaction['type'] === 'masuk') {
                $transaction['stock_before'] = $currentStock - $transaction['jumlah'];
                $transaction['stock_after'] = $currentStock;
            } else {
                $transaction['stock_before'] = $currentStock + $transaction['jumlah'];
                $transaction['stock_after'] = $currentStock;
            }
            
            // Move backwards in time
            $currentStock = $transaction['stock_before'];
            
            return $transaction;
        })->reverse()->values();

        return view('inventory.barang.transactions', compact('barang', 'transactions'));
    }
}
