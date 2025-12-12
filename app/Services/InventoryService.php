<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\DB;
use Exception;

class InventoryService
{
    /**
     * Add stock (Barang Masuk)
     * 
     * @param array $data validated data from request
     * @return BarangMasuk
     * @throws Exception
     */
    public function addStock(array $data): BarangMasuk 
    {
        // Validate quantity
        if ($data['jumlah'] <= 0) {
            throw new Exception('Jumlah barang masuk harus lebih dari 0.');
        }

        // Find barang
        $barang = Barang::find($data['barang_id']);
        
        if (!$barang) {
            throw new Exception('Barang tidak ditemukan.');
        }

        // Use Database Transaction for Atomicity (Availability - 5A)
        DB::beginTransaction();

        try {
            // Create BarangMasuk record
            $barangMasuk = BarangMasuk::create([
                'barang_id' => $data['barang_id'],
                'supplier_id' => $data['supplier_id'],
                'user_id' => auth()->id(), // Accountability
                'jumlah' => $data['jumlah'],
                'tanggal' => $data['tanggal'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            // Update stock atomically
            $barang->increment('stok', $data['jumlah']);

            DB::commit();

            return $barangMasuk;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Gagal menambah stok: ' . $e->getMessage());
        }
    }

    /**
     * Reduce stock (Barang Keluar)
     * 
     * @param array $data validated data from request
     * @return BarangKeluar
     * @throws Exception
     */
    public function reduceStock(array $data): BarangKeluar 
    {
        // Validate quantity
        if ($data['jumlah'] <= 0) {
            throw new Exception('Jumlah barang keluar harus lebih dari 0.');
        }

        // Find barang with lock for concurrent safety
        $barang = Barang::lockForUpdate()->find($data['barang_id']);
        
        if (!$barang) {
            throw new Exception('Barang tidak ditemukan.');
        }

        // Availability Check - Validate sufficient stock (5A)
        if (!$barang->hasSufficientStock($data['jumlah'])) {
            throw new Exception(
                "Stok tidak mencukupi. Stok tersedia: {$barang->stok}, diminta: {$data['jumlah']}."
            );
        }

        // Use Database Transaction for Atomicity (Availability - 5A)
        DB::beginTransaction();

        try {
            // Create BarangKeluar record
            $barangKeluar = BarangKeluar::create([
                'barang_id' => $data['barang_id'],
                'user_id' => auth()->id(), // Accountability
                'jumlah' => $data['jumlah'],
                'tanggal' => $data['tanggal'],
                'tujuan' => $data['tujuan'],
            ]);

            // Update stock atomically
            $barang->decrement('stok', $data['jumlah']);

            DB::commit();

            return $barangKeluar;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Gagal mengurangi stok: ' . $e->getMessage());
        }
    }

    /**
     * Get low stock items (below minimum stock)
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLowStockItems()
    {
        return Barang::lowStock()->get();
    }

    /**
     * Get out of stock items
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOutOfStockItems()
    {
        return Barang::outOfStock()->get();
    }

    /**
     * Get barang details with current stock status
     * 
     * @param int $barangId
     * @return array
     * @throws Exception
     */
    public function getStockStatus(int $barangId): array
    {
        $barang = Barang::find($barangId);

        if (!$barang) {
            throw new Exception('Barang tidak ditemukan.');
        }

        return [
            'barang' => $barang,
            'is_low_stock' => $barang->isBelowMinStock(),
            'is_out_of_stock' => $barang->stok == 0,
            'stock_percentage' => $barang->min_stok > 0 
                ? round(($barang->stok / $barang->min_stok) * 100, 2)
                : 100,
        ];
    }

    /**
     * Bulk update minimum stock
     * 
     * @param array $updates [['barang_id' => 1, 'min_stok' => 10], ...]
     * @return int
     */
    public function bulkUpdateMinStock(array $updates): int
    {
        DB::beginTransaction();

        try {
            $count = 0;
            
            foreach ($updates as $update) {
                $barang = Barang::find($update['barang_id']);
                
                if ($barang) {
                    $barang->update(['min_stok' => $update['min_stok']]);
                    $count++;
                }
            }

            DB::commit();
            
            return $count;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Gagal update minimum stok: ' . $e->getMessage());
        }
    }
}
