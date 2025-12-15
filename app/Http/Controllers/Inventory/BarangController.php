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

    /**
     * Bulk delete transactions for a specific barang
     */
    public function bulkDeleteTransactions(Request $request, Barang $barang): RedirectResponse
    {
        Gate::authorize('isAdmin'); // Only admin can delete transactions

        $validated = $request->validate([
            'transaction_ids' => ['required', 'array'],
            'transaction_ids.*' => ['required', 'string'], // Format: "masuk-1" or "keluar-2"
        ]);

        $deletedCount = 0;
        $errors = [];

        foreach ($validated['transaction_ids'] as $transactionId) {
            try {
                [$type, $id] = explode('-', $transactionId);
                
                if ($type === 'masuk') {
                    $transaction = \App\Models\BarangMasuk::findOrFail($id);
                    
                    // Verify this transaction belongs to the current barang
                    if ($transaction->barang_id !== $barang->id) {
                        throw new \Exception("Transaksi tidak sesuai dengan barang.");
                    }

                    // Reverse stock (subtract the amount that was added)
                    $barang->stok -= $transaction->jumlah;
                    
                    if ($barang->stok < 0) {
                        throw new \Exception("Penghapusan ini akan menyebabkan stok negatif. Hapus transaksi keluar terlebih dahulu.");
                    }

                    // Log to audit before deletion
                    \App\Models\AuditLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'deleted',
                        'table_name' => 'barang_masuk',
                        'old_values' => $transaction->toArray(),
                        'new_values' => null,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]);

                    $transaction->delete();
                    
                } elseif ($type === 'keluar') {
                    $transaction = \App\Models\BarangKeluar::findOrFail($id);
                    
                    // Verify this transaction belongs to the current barang
                    if ($transaction->barang_id !== $barang->id) {
                        throw new \Exception("Transaksi tidak sesuai dengan barang.");
                    }

                    // Reverse stock (add back the amount that was removed)
                    $barang->stok += $transaction->jumlah;

                    // Log to audit before deletion
                    \App\Models\AuditLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'deleted',
                        'table_name' => 'barang_keluar',
                        'old_values' => $transaction->toArray(),
                        'new_values' => null,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]);

                    $transaction->delete();
                    
                } else {
                    throw new \Exception("Tipe transaksi tidak valid.");
                }

                $deletedCount++;

            } catch (\Exception $e) {
                $errors[] = "Error deleting transaction {$transactionId}: " . $e->getMessage();
            }
        }

        // Save the stock changes
        $barang->save();

        // Log the bulk deletion summary to audit
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'bulk_delete_transactions',
            'table_name' => 'barang',
            'old_values' => [
                'barang_id' => $barang->id,
                'barang_kode' => $barang->kode_barang,
                'barang_nama' => $barang->nama_barang,
                'deleted_count' => $deletedCount,
                'transaction_ids' => $validated['transaction_ids'],
                'old_stock' => $barang->getOriginal('stok'),
            ],
            'new_values' => [
                'new_stock' => $barang->stok,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        if (!empty($errors)) {
            return redirect()
                ->route('inventory.barang.transactions', $barang)
                ->with('warning', "{$deletedCount} transaksi berhasil dihapus. Beberapa error: " . implode('; ', $errors));
        }

        return redirect()
            ->route('inventory.barang.transactions', $barang)
            ->with('success', "{$deletedCount} transaksi berhasil dihapus dan stok telah disesuaikan.");
    }
}
