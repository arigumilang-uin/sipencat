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
}
