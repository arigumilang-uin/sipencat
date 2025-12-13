<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Barang;
use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create notification for specific user
     */
    public function create(int $userId, string $type, string $title, string $message, ?array $data = null, string $icon = 'bi-bell', string $color = 'primary'): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'icon' => $icon,
            'color' => $color,
        ]);
    }

    /**
     * Notify admins about user creation
     */
    public function notifyAdminsUserCreated(User $newUser): void
    {
        $admins = User::where('role', UserRole::ADMIN)
            ->where('is_active', true)
            ->get();

        foreach ($admins as $admin) {
            $this->create(
                userId: $admin->id,
                type: 'user_created',
                title: 'User Baru Ditambahkan',
                message: "User {$newUser->name} ({$newUser->role->label()}) telah ditambahkan ke sistem.",
                data: ['user_id' => $newUser->id],
                icon: 'bi-person-plus',
                color: 'info'
            );
        }
    }

    /**
     * Notify admins about user status change
     */
    public function notifyAdminsUserStatusChanged(User $user, bool $oldStatus): void
    {
        $admins = User::where('role', UserRole::ADMIN)
            ->where('is_active', true)
            ->where('id', '!=', $user->id) // Don't notify self
            ->get();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        foreach ($admins as $admin) {
            $this->create(
                userId: $admin->id,
                type: 'user_status_changed',
                title: 'Status User Berubah',
                message: "User {$user->name} telah {$status}.",
                data: ['user_id' => $user->id, 'old_status' => $oldStatus, 'new_status' => $user->is_active],
                icon: 'bi-person-x',
                color: $user->is_active ? 'success' : 'warning'
            );
        }
    }

    /**
     * Notify Admin & Gudang about low stock
     */
    public function notifyLowStock(Barang $barang): void
    {
        $users = User::whereIn('role', [UserRole::ADMIN, UserRole::GUDANG])
            ->where('is_active', true)
            ->get();

        foreach ($users as $user) {
            $this->create(
                userId: $user->id,
                type: 'low_stock',
                title: 'Stok Rendah',
                message: "{$barang->nama_barang} stok rendah! Tersisa {$barang->stok} (min: {$barang->min_stok})",
                data: ['barang_id' => $barang->id, 'stok' => $barang->stok, 'min_stok' => $barang->min_stok],
                icon: 'bi-exclamation-triangle',
                color: 'warning'
            );
        }
    }

    /**
     * Notify Admin & Gudang about out of stock
     */
    public function notifyOutOfStock(Barang $barang): void
    {
        $users = User::whereIn('role', [UserRole::ADMIN, UserRole::GUDANG])
            ->where('is_active', true)
            ->get();

        foreach ($users as $user) {
            $this->create(
                userId: $user->id,
                type: 'out_of_stock',
                title: 'Stok Habis',
                message: "{$barang->nama_barang} stok HABIS! Segera lakukan restock.",
                data: ['barang_id' => $barang->id],
                icon: 'bi-x-circle',
                color: 'danger'
            );
        }
    }

    /**
     * Notify Pemilik about low stock summary (weekly)
     */
    public function notifyPemilikLowStockSummary(): void
    {
        $pemilik = User::where('role', UserRole::PEMILIK)
            ->where('is_active', true)
            ->get();

        $lowStockCount = Barang::lowStock()->count();
        $outStockCount = Barang::outOfStock()->count();

        if ($lowStockCount > 0 || $outStockCount > 0) {
            foreach ($pemilik as $owner) {
                $this->create(
                    userId: $owner->id,
                    type: 'inventory_summary',
                    title: 'Ringkasan Inventory',
                    message: "Terdapat {$lowStockCount} barang stok rendah dan {$outStockCount} barang stok habis. Perlu perhatian untuk restock.",
                    data: ['low_stock_count' => $lowStockCount, 'out_stock_count' => $outStockCount],
                    icon: 'bi-graph-down',
                    color: 'warning'
                );
            }
        }
    }

    /**
     * Notify Admin & Gudang about large transaction
     */
    public function notifyLargeTransaction(string $type, string $barangName, int $quantity): void
    {
        $users = User::whereIn('role', [UserRole::ADMIN, UserRole::GUDANG])
            ->where('is_active', true)
            ->get();

        $typeLabel = $type === 'masuk' ? 'Barang Masuk' : 'Barang Keluar';

        foreach ($users as $user) {
            $this->create(
                userId: $user->id,
                type: 'large_transaction',
                title: "Transaksi Besar: {$typeLabel}",
                message: "{$barangName} - Qty: {$quantity}",
                data: ['type' => $type, 'barang' => $barangName, 'quantity' => $quantity],
                icon: 'bi-box',
                color: 'info'
            );
        }
    }

    /**
     * Notify Pemilik tentang critical stock alert (threshold-based)
     * Triggered when > 30% items are low/out of stock
     */
    public function notifyPemilikCriticalStockAlert(): void
    {
        $totalBarang = Barang::count();
        $criticalCount = Barang::lowStock()->count() + Barang::outOfStock()->count();
        
        // Only notify if more than 30% items are critical
        if ($totalBarang > 0 && ($criticalCount / $totalBarang) >= 0.3) {
            $pemilik = User::where('role', UserRole::PEMILIK)
                ->where('is_active', true)
                ->get();

            $percentage = round(($criticalCount / $totalBarang) * 100);

            foreach ($pemilik as $owner) {
                $this->create(
                    userId: $owner->id,
                    type: 'critical_stock_alert',
                    title: 'Alert: Stok Kritis!',
                    message: "{$percentage}% inventory ({$criticalCount} dari {$totalBarang} item) memerlukan restock segera. Perlu keputusan pembelian.",
                    data: [
                        'total_items' => $totalBarang,
                        'critical_count' => $criticalCount,
                        'percentage' => $percentage
                    ],
                    icon: 'bi-exclamation-octagon',
                    color: 'danger'
                );
            }
        }
    }

    /**
     * Notify Pemilik tentang inventory value snapshot
     * Daily/Weekly business insight
     */
    public function notifyPemilikInventoryValue(): void
    {
        $pemilik = User::where('role', UserRole::PEMILIK)
            ->where('is_active', true)
            ->get();

        $totalValue = Barang::sum(\Illuminate\Support\Facades\DB::raw('stok * harga'));
        $totalItems = Barang::count();
        $totalStok = Barang::sum('stok');

        foreach ($pemilik as $owner) {
            $this->create(
                userId: $owner->id,
                type: 'inventory_value',
                title: 'Nilai Inventory Hari Ini',
                message: "Total Nilai: Rp " . number_format($totalValue, 0, ',', '.') . " ({$totalItems} item, {$totalStok} unit)",
                data: [
                    'total_value' => $totalValue,
                    'total_items' => $totalItems,
                    'total_stock' => $totalStok,
                    'date' => now()->toDateString()
                ],
                icon: 'bi-cash-stack',
                color: 'success'
            );
        }
    }

    /**
     * Notify Pemilik when many items out of stock (business impact)
     */
    public function notifyPemilikMultipleOutOfStock(): void
    {
        $outOfStockCount = Barang::outOfStock()->count();
        
        // Only notify if 3+ items are out of stock
        if ($outOfStockCount >= 3) {
            $pemilik = User::where('role', UserRole::PEMILIK)
                ->where('is_active', true)
                ->get();

            $outOfStockItems = Barang::outOfStock()
                ->limit(5)
                ->pluck('nama_barang')
                ->join(', ');

            foreach ($pemilik as $owner) {
                $this->create(
                    userId: $owner->id,
                    type: 'multiple_out_of_stock',
                    title: 'Banyak Barang Stok Habis',
                    message: "{$outOfStockCount} barang stok habis (termasuk: {$outOfStockItems}). Ini dapat mempengaruhi operasional.",
                    data: [
                        'count' => $outOfStockCount,
                        'items' => $outOfStockItems
                    ],
                    icon: 'bi-box-seam-fill',
                    color: 'danger'
                );
            }
        }
    }

    /**
     * Notify Pemilik tentang high activity day
     * E.g., when transaction count is unusually high
     */
    public function notifyPemilikHighActivity(int $transactionCount, string $date): void
    {
        $pemilik = User::where('role', UserRole::PEMILIK)
            ->where('is_active', true)
            ->get();

        foreach ($pemilik as $owner) {
            $this->create(
                userId: $owner->id,
                type: 'high_activity',
                title: 'Aktivitas Tinggi Terdeteksi',
                message: "Terdapat {$transactionCount} transaksi pada {$date}. Aktivitas gudang sedang tinggi.",
                data: [
                    'transaction_count' => $transactionCount,
                    'date' => $date
                ],
                icon: 'bi-activity',
                color: 'info'
            );
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId): bool
    {
        $notification = Notification::find($notificationId);
        
        if ($notification && $notification->user_id === auth()->id()) {
            return $notification->markAsRead();
        }

        return false;
    }

    /**
     * Mark all user notifications as read
     */
    public function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Get unread count for user
     */
    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->count();
    }

    /**
     * Get recent notifications for user
     */
    public function getRecent(int $userId, int $limit = 10)
    {
        return Notification::where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
