<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_toko',
        'nomor_struk',
        'tanggal',
        'daftar_barang', // untuk menyimpan array items termasuk nama barang
        'total',
        'jumlah_item',
        'bukti_pembayaran'
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'daftar_barang' => 'array', // otomatis konversi JSON ke array
        'total' => 'decimal:2'
    ];

    // Accessor untuk memastikan daftar_barang selalu return array
    public function getDaftarBarangAttribute($value)
    {
        try {
            return is_array($value) ? $value : json_decode($value, true) ?? [];
        } catch (\Exception $e) {
            Log::error('Gagal decode daftar_barang:', ['id' => $this->id, 'value' => $value]);
            return [];
        }
    }

    // Mutator untuk menyimpan array sebagai JSON
    public function setDaftarBarangAttribute($value)
    {
        $this->attributes['daftar_barang'] = is_array($value) ? json_encode($value) : $value;
    }
}