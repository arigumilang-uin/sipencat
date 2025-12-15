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

        // Working Hours Management
        Route::get('working-hours', [\App\Http\Controllers\Admin\WorkingHourController::class, 'index'])->name('working-hours.index');
        Route::post('working-hours', [\App\Http\Controllers\Admin\WorkingHourController::class, 'store'])->name('working-hours.store');
        Route::put('working-hours/{workingHour}', [\App\Http\Controllers\Admin\WorkingHourController::class, 'update'])->name('working-hours.update');
        Route::delete('working-hours/{workingHour}', [\App\Http\Controllers\Admin\WorkingHourController::class, 'destroy'])->name('working-hours.destroy');
        Route::post('working-hours/{workingHour}/toggle', [\App\Http\Controllers\Admin\WorkingHourController::class, 'toggle'])->name('working-hours.toggle');

        // Shift Management
        Route::get('shifts', [\App\Http\Controllers\Admin\ShiftController::class, 'index'])->name('shifts.index');
        Route::post('shifts', [\App\Http\Controllers\Admin\ShiftController::class, 'store'])->name('shifts.store');
        Route::put('shifts/{shift}', [\App\Http\Controllers\Admin\ShiftController::class, 'update'])->name('shifts.update');
        Route::delete('shifts/{shift}', [\App\Http\Controllers\Admin\ShiftController::class, 'destroy'])->name('shifts.destroy');
        Route::post('shifts/{shift}/add-member', [\App\Http\Controllers\Admin\ShiftController::class, 'addMember'])->name('shifts.add-member');
        Route::delete('shifts/{shift}/members/{user}', [\App\Http\Controllers\Admin\ShiftController::class, 'removeMember'])->name('shifts.remove-member');
        Route::post('shifts/{shift}/toggle', [\App\Http\Controllers\Admin\ShiftController::class, 'toggle'])->name('shifts.toggle');

        // Overtime Management
        Route::get('overtime', [\App\Http\Controllers\Admin\AdminOvertimeController::class, 'index'])->name('overtime.index');
        Route::post('overtime/{overtime}/approve', [\App\Http\Controllers\OvertimeController::class, 'approve'])->name('overtime.approve');
        Route::post('overtime/{overtime}/reject', [\App\Http\Controllers\OvertimeController::class, 'reject'])->name('overtime.reject');
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
        Route::get('/', [\App\Http\Controllers\Report\ReportController::class, 'index'])->name('index');
        Route::get('/stock', [\App\Http\Controllers\Report\ReportController::class, 'stock'])->name('stock');
        Route::get('/barang-masuk', [\App\Http\Controllers\Report\ReportController::class, 'barangMasuk'])->name('barang-masuk');
        Route::get('/barang-keluar', [\App\Http\Controllers\Report\ReportController::class, 'barangKeluar'])->name('barang-keluar');
        Route::get('/mutation', [\App\Http\Controllers\Report\ReportController::class, 'mutation'])->name('mutation');
        
        // Supplier Report - RESTRICTED (Strategic Data)
        // Only ADMIN & PEMILIK - contains sensitive business relationships
        Route::get('/supplier', [\App\Http\Controllers\Report\ReportController::class, 'supplier'])
            ->name('supplier')
            ->middleware('can:canViewSupplierReport');
    });

    // =============================================
    // PROFILE ROUTES (All authenticated users)
    // =============================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Profile\ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [\App\Http\Controllers\Profile\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [\App\Http\Controllers\Profile\ProfileController::class, 'update'])->name('update');
        
        Route::get('/password', [\App\Http\Controllers\Profile\ProfileController::class, 'editPassword'])->name('password.edit');
        Route::put('/password', [\App\Http\Controllers\Profile\ProfileController::class, 'updatePassword'])->name('password.update');
        
        Route::get('/settings', [\App\Http\Controllers\Profile\ProfileController::class, 'settings'])->name('settings');
        Route::post('/deactivate', [\App\Http\Controllers\Profile\ProfileController::class, 'deactivate'])->name('deactivate');
    });

    // =============================================
    // NOTIFICATION ROUTES (All authenticated users)
    // =============================================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [\App\Http\Controllers\NotificationController::class, 'getUnread'])->name('unread');
        Route::post('/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('read-all');
    });

    // =============================================
    // WORK ENDED & OVERTIME ROUTES
    // =============================================
    Route::get('/work-ended', function () {
        return view('work-ended');
    })->name('work.ended');

    Route::post('/overtime/request', [\App\Http\Controllers\OvertimeController::class, 'request'])->name('overtime.request');

    // =============================================
    // SHIFT INFO ROUTE (For staff operasional)
    // =============================================
    Route::get('/my-shift', [\App\Http\Controllers\ShiftInfoController::class, 'index'])->name('shift.info');
});
