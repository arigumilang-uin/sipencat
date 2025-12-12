<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_supplier',
        'telp',
        'alamat',
    ];

    /**
     * Relasi ke BarangMasuk
     */
    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }
}
