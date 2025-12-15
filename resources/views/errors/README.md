# Custom Error Pages - SIPENCAT

Dokumentasi lengkap halaman error kustom untuk aplikasi SIPENCAT.

## 📋 Daftar Error Pages

Aplikasi ini memiliki custom error pages untuk HTTP status codes berikut:

### 1. **401 - Unauthorized** (`401.blade.php`)
- **Deskripsi**: User belum login/terauthentikasi
- **Icon**: `bi-shield-lock-fill`
- **Warna**: Rose/Pink gradient
- **Aksi**: Tombol "Login Sekarang" yang redirect ke halaman login
- **Kapan Muncul**: Ketika user mencoba mengakses halaman yang memerlukan autentikasi tanpa login

### 2. **402 - Payment Required** (`402.blade.php`)
- **Deskripsi**: Akses berbayar diperlukan
- **Icon**: `bi-credit-card-2-front`
- **Warna**: Emerald/Teal gradient
- **Kapan Muncul**: Jika sistem menggunakan fitur berbayar (jarang digunakan)

### 3. **403 - Forbidden** (`403.blade.php`)
- **Deskripsi**: User tidak memiliki permission/izin akses
- **Icon**: `bi-shield-exclamation`
- **Warna**: Orange/Red gradient
- **Info Tambahan**: 
  - Kemungkinan penyebab (role tidak sesuai, fitur khusus admin, dll)
- **Kapan Muncul**: Ketika user login tapi tidak punya permission untuk mengakses resource tertentu (mis: staff operasional coba akses menu admin)

### 4. **404 - Not Found** (`404.blade.php`)
- **Deskripsi**: Halaman tidak ditemukan
- **Icon**: `bi-search`
- **Warna**: Blue/Indigo gradient
- **Info Tambahan**: 
  - Saran troubleshooting (cek URL, gunakan navigasi, dll)
- **Kapan Muncul**: URL tidak valid atau resource tidak ada di database

### 5. **419 - Page Expired** (`419.blade.php`)
- **Deskripsi**: CSRF token expired/sesi kedaluwarsa
- **Icon**: `bi-clock-history`
- **Warna**: Amber/Orange gradient
- **Aksi**: Tombol "Muat Ulang Halaman" untuk refresh
- **Info Tambahan**: Penjelasan kenapa terjadi (keamanan, tidak aktif lama)
- **Kapan Muncul**: Form submission dengan CSRF token yang sudah expired

### 6. **429 - Too Many Requests** (`429.blade.php`)
- **Deskripsi**: Rate limit exceeded
- **Icon**: `bi-hourglass-split`
- **Warna**: Purple/Pink gradient
- **Aksi**: Tombol auto-reload dengan countdown 5 detik
- **Info Tambahan**: Penjelasan rate limit protection
- **Kapan Muncul**: User melakukan terlalu banyak request dalam waktu singkat (throttle middleware)

### 7. **500 - Internal Server Error** (`500.blade.php`)
- **Deskripsi**: Server error/bug dalam kode
- **Icon**: `bi-exclamation-octagon-fill`
- **Warna**: Red/Rose gradient
- **Aksi**: Tombol "Muat Ulang Halaman"
- **Info Tambahan**: 
  - Saran apa yang bisa dilakukan user
  - Debug info (hanya tampil jika `APP_DEBUG=true`)
- **Kapan Muncul**: Exception tidak tertangani, database error, dll

### 8. **503 - Service Unavailable** (`503.blade.php`)
- **Deskripsi**: Sistem dalam maintenance mode
- **Icon**: `bi-cone-striped` dengan animasi spin
- **Warna**: Slate/Gray gradient
- **Aksi**: Tombol "Periksa Status Sistem" (reload)
- **Info Tambahan**: 
  - Penjelasan maintenance
  - Yang sedang dilakukan tim
- **Kapan Muncul**: Saat menjalankan `php artisan down` untuk maintenance

## 🎨 Design System

### Layout (`layout.blade.php`)
Semua error pages menggunakan layout yang sama dengan komponen:

**Brand Section:**
- Logo SIPENCAT dengan gradient indigo/purple
- Tagline "Asset System"

**Error Card:**
- Rounded 3xl dengan shadow dan ring
- Header gradient (berbeda per error type)
- Icon dalam circle dengan backdrop blur
- Error code (besar, bold)
- Error title

**Content:**
- Message title (bold, besar)
- Message body (deskripsi lengkap)
- Additional content (tips, info box, dll)
- Action buttons (primary action + back button)

**Footer:**
- Informasi kontak admin
- Copyright SIPENCAT

### Color Mapping
```
401 - Rose/Pink       (from-rose-500 to-pink-600)
402 - Emerald/Teal    (from-emerald-500 to-teal-600)
403 - Orange/Red      (from-orange-500 to-red-600)
404 - Blue/Indigo     (from-blue-500 to-indigo-600)
419 - Amber/Orange    (from-amber-500 to-orange-600)
429 - Purple/Pink     (from-purple-500 to-pink-600)
500 - Red/Rose        (from-red-500 to-rose-600)
503 - Slate/Gray      (from-slate-500 to-slate-700)
```

### Icon Mapping
```
401 - bi-shield-lock-fill           (Locked shield)
402 - bi-credit-card-2-front        (Credit card)
403 - bi-shield-exclamation         (Shield warning)
404 - bi-search                     (Search/magnifying glass)
419 - bi-clock-history              (Clock)
429 - bi-hourglass-split            (Hourglass)
500 - bi-exclamation-octagon-fill   (Stop sign)
503 - bi-cone-striped               (Construction cone)
```

## 🔧 Customization

### Mengubah Pesan Error
Edit file blade yang sesuai di `resources/views/errors/`:

```blade
@section('message-title', 'Judul Pesan Baru')
@section('message-body')
    Deskripsi lengkap error...
@endsection
```

### Menambah Info Box
```blade
@section('additional-content')
    <div class="rounded-xl bg-blue-50 border border-blue-100 p-4">
        <p>Informasi tambahan...</p>
    </div>
@endsection
```

### Custom Action Button
```blade
@section('action-buttons')
    <a href="/custom-link" class="btn...">
        Custom Action
    </a>
@endsection
```

### Hide Home Button
```blade
@php
    $hideHomeButton = true;
@endphp
```

## 🧪 Testing Error Pages

### Development
Untuk test error pages di development:

```php
// routes/web.php
Route::get('/test-error/{code}', function ($code) {
    abort($code);
})->middleware('web');
```

Lalu akses: `http://localhost:8000/test-error/404`

### Production
Pastikan `APP_DEBUG=false` di `.env` untuk melihat custom error pages.

Debug info hanya muncul di error 500 jika `APP_DEBUG=true`.

## 📝 Best Practices

### ✅ DO's:
- Gunakan Bahasa Indonesia yang sopan dan mudah dipahami
- Berikan saran/solusi yang actionable
- Gunakan icon yang relevan
- Maintain konsistensi design
- Test semua error pages

### ❌ DON'Ts:
- Jangan tampilkan stack trace atau error message teknis ke user
- Jangan gunakan istilah teknis yang tidak dipahami user
- Jangan buat pesan yang menyalahkan user
- Jangan lupakan responsive design

## 🔐 Security Notes

- Error 500 hanya menampilkan debug info jika `APP_DEBUG=true`
- Tidak ada informasi sensitif sistem yang ditampilkan
- CSRF token refresh di error 419
- Rate limit info di error 429 untuk aware user

## 📚 Laravel Documentation

Referensi official:
- [HTTP Errors](https://laravel.com/docs/11.x/errors#http-exceptions)
- [Custom Error Pages](https://laravel.com/docs/11.x/errors#custom-http-error-pages)

---

**Created**: 2025-12-15  
**Version**: 1.0  
**Maintained by**: SIPENCAT Development Team
