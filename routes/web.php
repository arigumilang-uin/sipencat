<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Inventory\BarangKeluarController;
use App\Http\Controllers\Inventory\BarangMasukController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SIPENCAT
|--------------------------------------------------------------------------
|
| Route structure organized by authentication and authorization levels:
| - Public routes (Guest)
| - Authenticated routes (All users)
| - Admin routes (Admin only)
| - Inventory routes (Admin & Gudang)
|
*/

// =============================================
// PUBLIC ROUTES (Guest)
// =============================================
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

// =============================================
// AUTHENTICATED ROUTES (All logged-in users)
// =============================================
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard - Role-based
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role-specific dashboards
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/admin', [DashboardController::class, 'adminDashboard'])
            ->name('admin')
            ->can('isAdmin');

        Route::get('/gudang', [DashboardController::class, 'gudangDashboard'])
            ->name('gudang')
            ->can('isGudang');

        Route::get('/pemilik', [DashboardController::class, 'pemilikDashboard'])
            ->name('pemilik')
            ->can('isPemilik');
    });

    // =============================================
    // ADMIN ROUTES (Admin only)
    // =============================================
    Route::prefix('admin')->name('admin.')->middleware('can:isAdmin')->group(function () {
        // User Management
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // Audit Logs
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
    });

    // =============================================
    // INVENTORY ROUTES (Admin & Gudang)
    // =============================================
    Route::prefix('inventory')->name('inventory.')->middleware('can:canManageInventory')->group(function () {
        // Master Data - Barang
        Route::resource('barang', \App\Http\Controllers\Inventory\BarangController::class);

        // Master Data - Supplier
        Route::resource('supplier', \App\Http\Controllers\Inventory\SupplierController::class);

        // Barang Masuk
        Route::resource('barang-masuk', BarangMasukController::class)->parameters([
            'barang-masuk' => 'barangMasuk'
        ]);

        // Barang Keluar
        Route::resource('barang-keluar', BarangKeluarController::class)->parameters([
            'barang-keluar' => 'barangKeluar'
        ]);
    });

    // =============================================
    // REPORT ROUTES (All authenticated users)
    // =============================================
    Route::prefix('reports')->name('reports.')->middleware('can:canViewReports')->group(function () {
        // Reports will be added later
        Route::get('/', function () {
            return view('reports.index');
        })->name('index');
    });
});
