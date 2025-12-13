<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\User;
use App\Observers\AuditLogObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register AuditLog Observer for Auditability (5A)
        User::observe(AuditLogObserver::class);
        Barang::observe(AuditLogObserver::class);
        BarangMasuk::observe(AuditLogObserver::class);
        BarangKeluar::observe(AuditLogObserver::class);

        // Register Barang Observer for Notifications
        Barang::observe(\App\Observers\BarangObserver::class);

        // Define Authorization Gates (5A)
        Gate::define('isAdmin', function (User $user) {
            return $user->role === UserRole::ADMIN;
        });

        Gate::define('isStaffOperasional', function (User $user) {
            return $user->role === UserRole::STAFF_OPERASIONAL;
        });

        // Backward compatibility alias
        Gate::define('isGudang', function (User $user) {
            return $user->role === UserRole::STAFF_OPERASIONAL;
        });

        Gate::define('isPemilik', function (User $user) {
            return $user->role === UserRole::PEMILIK;
        });

        // Combined Gates for flexibility
        Gate::define('isAdminOrStaffOperasional', function (User $user) {
            return in_array($user->role, [UserRole::ADMIN, UserRole::STAFF_OPERASIONAL]);
        });

        // Backward compatibility
        Gate::define('isAdminOrGudang', function (User $user) {
            return in_array($user->role, [UserRole::ADMIN, UserRole::STAFF_OPERASIONAL]);
        });

        Gate::define('canManageInventory', function (User $user) {
            return in_array($user->role, [UserRole::ADMIN, UserRole::STAFF_OPERASIONAL]);
        });

        Gate::define('canViewReports', function (User $user) {
            return true; // All authenticated users can view reports
        });

        Gate::define('canManageUsers', function (User $user) {
            return $user->role === UserRole::ADMIN;
        });

        Gate::define('canViewAuditLogs', function (User $user) {
            return $user->role === UserRole::ADMIN;
        });
    }
}
