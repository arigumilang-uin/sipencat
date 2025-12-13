<?php

namespace App\Observers;

use App\Models\Barang;
use App\Services\NotificationService;

class BarangObserver
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the Barang "updated" event.
     */
    public function updated(Barang $barang): void
    {
        // Check if stock changed
        if ($barang->wasChanged('stok')) {
            // Notify Admin & Gudang about low stock
            if ($barang->isBelowMinStock() && $barang->stok > 0) {
                $this->notificationService->notifyLowStock($barang);
            }

            // Notify Admin & Gudang about out of stock
            if ($barang->stok == 0) {
                $this->notificationService->notifyOutOfStock($barang);
            }

            // Notify Pemilik if critical threshold reached (30%+ items low/out)
            $this->notificationService->notifyPemilikCriticalStockAlert();

            // Notify Pemilik if multiple items are out of stock (3+)
            $this->notificationService->notifyPemilikMultipleOutOfStock();
        }
    }
}
