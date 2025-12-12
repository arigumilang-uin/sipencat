<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers
     */
    public function index(): View
    {
        Gate::authorize('canManageInventory');

        $suppliers = Supplier::latest()->paginate(15);

        return view('inventory.supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier
     */
    public function create(): View
    {
        Gate::authorize('canManageInventory');

        return view('inventory.supplier.create');
    }

    /**
     * Store a newly created supplier
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('canManageInventory');

        $validated = $request->validate([
            'nama_supplier' => ['required', 'string', 'max:255'],
            'telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi',
            'telp.required' => 'Nomor telepon wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
        ]);

        Supplier::create($validated);

        return redirect()
            ->route('inventory.supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Display the specified supplier
     */
    public function show(Supplier $supplier): View
    {
        Gate::authorize('canManageInventory');

        return view('inventory.supplier.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier
     */
    public function edit(Supplier $supplier): View
    {
        Gate::authorize('canManageInventory');

        return view('inventory.supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        Gate::authorize('canManageInventory');

        $validated = $request->validate([
            'nama_supplier' => ['required', 'string', 'max:255'],
            'telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi',
            'telp.required' => 'Nomor telepon wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('inventory.supplier.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    /**
     * Remove the specified supplier
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        Gate::authorize('isAdmin'); // Only admin can delete

        // Check if supplier has transactions
        if ($supplier->barangMasuk()->count() > 0) {
            return back()->with('error', 'Supplier tidak dapat dihapus karena memiliki riwayat transaksi.');
        }

        $supplier->delete();

        return redirect()
            ->route('inventory.supplier.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}
