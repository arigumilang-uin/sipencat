<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'barang';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'stok',
        'harga',
        'min_stok',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'stok' => 'integer',
        'harga' => 'decimal:2',
        'min_stok' => 'integer',
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
     * Check if stok is below minimum
     */
    public function isBelowMinStock(): bool
    {
        return $this->stok < $this->min_stok;
    }

    /**
     * Check if stok is sufficient for withdrawal
     */
    public function hasSufficientStock(int $amount): bool
    {
        return $this->stok >= $amount;
    }

    /**
     * Scope untuk barang dengan stok di bawah minimum
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stok', '<', 'min_stok');
    }

    /**
     * Scope untuk barang dengan stok habis
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stok', 0);
    }
}
