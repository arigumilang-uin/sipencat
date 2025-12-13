# 🔄 ROLE RENAME & SHIFT MANAGEMENT IMPLEMENTATION

## 📝 RINGKASAN PERUBAHAN

### ✅ **Nama Role Baru: STAFF OPERASIONAL**

**Perubahan:**
- ❌ OLD: "gudang" (Label: "Staff Gudang")
- ✅ NEW: "staff_operasional" (Label: "Staff Operasional")

**Alasan:**
- ✅ Lebih profesional dan formal
- ✅ Cakupan lebih luas (tidak terbatas gudang saja)
- ✅ Sesuai standar enterprise system
- ✅ Scalable untuk future development

---

## 🚀 FITUR BARU: SHIFT MANAGEMENT SYSTEM

### **Konsep:**
Admin dapat mengelompokkan beberapa user (Staff Operasional) ke dalam **SHIFT**, kemudian mengatur jam kerja berdasarkan shift tersebut.

### **Keuntungan:**
✅ **Efisien:** Set jam kerja sekali untuk banyak user  
✅ **Fleksibel:** User bisa pindah shift dengan mudah  
✅ **Organized:** Grouping user berdasarkan jadwal kerja  
✅ **Scalable:** Support multiple shifts (Pagi, Sore, Malam)  

### **Example Flow:**

```
Step 1: Admin buat Shift
-------------------------------
Nama: Shift Pagi
Deskripsi: Shift pagi 08:00-17:00
Members: User A, User B, User C

Nama: Shift Sore
Deskripsi: Shift sore 14:00-23:00
Members: User D, User E

Step 2: Admin atur Jam Kerja per Shift
-------------------------------
Shift: Shift Pagi
Hari: Senin-Jumat
Jam: 08:00-17:00

Shift: Shift Sore
Hari: Senin-Jumat
Jam: 14:00-23:00

Result:
-------------------------------
User A, B, C → Login hours: Mon-Fri 08:00-17:00
User D, E → Login hours: Mon-Fri 14:00-23:00
```

---

## 📊 DATABASE SCHEMA CHANGES

### **New Tables:**

#### 1. `shifts`
```sql
id | name | description | is_active | created_at | updated_at
1  | Shift Pagi | Pagi 08:00-17:00 | 1 | ... | ...
2  | Shift Sore | Sore 14:00-23:00 | 1 | ... | ...
```

#### 2. `shift_members` (Pivot Table)
```sql
id | shift_id | user_id | created_at | updated_at
1  | 1        | 5       | ... | ...
2  | 1        | 6       | ... | ...
3  | 2        | 7       | ... | ...

Constraint: user_id UNIQUE (user hanya bisa di 1 shift)
```

### **Updated Tables:**

#### 1. `working_hours`
```sql
BEFORE:
id | role | day_of_week | start_time | end_time | is_active

AFTER:
id | shift_id | role | day_of_week | start_time | end_time | is_active

New Logic:
- Jika shift_id NOT NULL → Apply ke semua user di shift tersebut
- Jika shift_id NULL && role NOT NULL → Apply ke role (backward compatible)
```

#### 2. `users`
```sql
role ENUM:
BEFORE: ('admin', 'gudang', 'pemilik')
AFTER: ('admin', 'staff_operasional', 'pemilik')

Data migrated automatically:
'gudang' → 'staff_operasional'
```

---

## 🔧 FILES YANG PERLU DIUPDATE

### ✅ **SUDAH DIUPDATE:**
1. `database/migrations/.../rename_gudang_role_and_create_shifts_system.php`
2. `app/Enums/UserRole.php`

### ⚠️ **PERLU DIUPDATE MANUAL:**

Karena banyak file yang reference "gudang" atau "GUDANG", berikut list lengkap:

#### **1. AppServiceProvider.php**
```php
// Line 43-44, 52-53, 56-57
Gate::define('isGudang', function (User $user) {
    return $user->role === UserRole::STAFF_OPERASIONAL; // ← Change ini
});

Gate::define('canManageInventory', function (User $user) {
    return in_array($user->role, [UserRole::ADMIN, UserRole::STAFF_OPERASIONAL]); // ← Change ini
});
```

#### **2. NotificationService.php**
```php
// Line 80, 102, 151
$users = User::whereIn('role', [UserRole::ADMIN, UserRole::STAFF_OPERASIONAL])
```

#### **3. WorkingHour.php Model**
```php
// Update validation untuk accept 'staff_operasional'
```

#### **4. Views - References di UI**
Search & Replace di semua file:
- "Gudang" → "Staff Operasional"
- "gudang" → "staff_operasional"  
- "Staff Gudang" → "Staff Operasional"

Files affected:
- `resources/views/admin/users/*.blade.php`
- `resources/views/dashboard/gudang.blade.php` → Rename to `staff-operasional.blade.php`
- `resources/views/layouts/sidebar.blade.php`
- `resources/views/admin/working-hours/index.blade.php`

---

## 🎯 NEXT STEPS (Implementation Priority)

### **HIGH PRIORITY (Must Do Now):**

1. ✅ Run migration:
```bash
php artisan migrate
```

2. ⚠️ Update AppServiceProvider Gates references

3. ⚠️ Update dashboard route untuk staff_operasional

4. ⚠️ Global search-replace "gudang" references di:
   - Controllers
   - Services  
   - Views

### **MEDIUM PRIORITY (Shift System):**

5. Create Shift Model
6. Create ShiftController (CRUD shifts)
7. Create Views untuk Shift Management
8. Update WorkingHourController untuk support shift
9. Update WorkingHour Model untuk check shift membership

### **LOW PRIORITY (Polish):**

10. Update Seeders jika ada
11. Update Tests jika ada
12. Update Documentation

---
## ⚡ QUICK FIX GUIDE

### **Fix 1: Update Gates (CRITICAL)**
File: `app/Providers/AppServiceProvider.php`

```php
// BEFORE:
Gate::define('isGudang', function (User $user) {
    return $user->role === UserRole::GUDANG;
});

// AFTER:
Gate::define('isStaffOperasional', function (User $user) {
    return $user->role === UserRole::STAFF_OPERASIONAL;
});

// Keep backward compatible alias:
Gate::define('isGudang', function (User $user) {
    return $user->role === UserRole::STAFF_OPERASIONAL;
});
```

### **Fix 2: Update Dashboard Route**
File: `routes/web.php`

```php
// BEFORE:
Route::get('/gudang', [DashboardController::class, 'gudangDashboard'])
    ->name('gudang')
    ->can('isGudang');

// AFTER:
Route::get('/staff-operasional', [DashboardController::class, 'staffOperasionalDashboard'])
    ->name('staff-operasional')
    ->can('isStaffOperasional');
```

### **Fix 3: Rename Dashboard View**
```bash
# Rename file
mv resources/views/dashboard/gudang.blade.php resources/views/dashboard/staff-operasional.blade.php
```

### **Fix 4: Update DashboardController**
```php
// Rename method:
public function staffOperasionalDashboard()
{
    // ...
}
```

---

## 🧪 TESTING CHECKLIST

### **After Migration:**
```
✅ Check users table → All 'gudang' users now 'staff_operasional'
✅ Check working_hours table → shift_id column added
✅ Check shifts table → Created successfully
✅ Check shift_members table → Created successfully
```

### **After Code Update:**
```
✅ Login sebagai Staff Operasional → Success
✅ Access Dashboard → Show correct dashboard
✅ Sidebar menu → Show correct label
✅ User management → Role shows "Staff Operasional"
✅ Working hours → Can set for "Staff Operasional"
```

---

## ⚠️ IMPORTANT NOTES

1. **Backward Compatibility:** 
   - `isGudang()` method tetap ada (aliasing ke `isStaffOperasional()`)
   - Gates `isGudang` tetap bisa dipakai
   
2. **Data Migration:**
   - Semua user dengan role "gudang" otomatis jadi "staff_operasional"
   - Semua working_hours dengan role "gudang" otomatis update
   
3. **Shift System:**
   - Optional! Bisa pakai role-based (old way) atau shift-based (new way)
   - Shift-based lebih flexible untuk multi-user management

---

## 🎉 BENEFIT SUMMARY

### **Role Rename:**
✅ Lebih profesional ("Staff Operasional" vs "Staff Gudang")  
✅ Cakupan lebih luas (inventory operations, bukan hanya gudang)  
✅ Sesuai standar enterprise  

### **Shift Management:**
✅ Group multiple users easily  
✅ Set working hours once untuk banyak user  
✅ Support multiple shifts (Pagi, Sore, Malam)  
✅ Easy user reshuffling antar shift  
✅ Scalable untuk company growth  

---

**Status:** Migration & Enum DONE ✅  
**Next:** Update references across codebase ⚠️  
**ETA:** ~30 minutes untuk complete all updates  

Apakah mau saya lanjutkan untuk implement Shift Management UI sekarang atau update references dulu?
