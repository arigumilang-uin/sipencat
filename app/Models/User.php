<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'role' => UserRole::class,
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    /**
     * Relasi ke BarangMasuk
     */
    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }

    /**
     * Relasi ke BarangKeluar
     */
    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class);
    }

    /**
     * Relasi ke AuditLog
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Relasi ke Notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relasi ke Shift (many-to-many via shift_members)
     */
    public function shift()
    {
        return $this->belongsToMany(Shift::class, 'shift_members')->withTimestamps();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Check if user is staff operasional
     */
    public function isStaffOperasional(): bool
    {
        return $this->role === UserRole::STAFF_OPERASIONAL;
    }

    /**
     * Backward compatibility: Check if user is gudang (alias to isStaffOperasional)
     */
    public function isGudang(): bool
    {
        return $this->isStaffOperasional();
    }

    /**
     * Check if user is pemilik
     */
    public function isPemilik(): bool
    {
        return $this->role === UserRole::PEMILIK;
    }

    /**
     * Scope untuk filter user aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter berdasarkan role
     */
    public function scopeRole($query, UserRole $role)
    {
        return $query->where('role', $role->value);
    }
}
