<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Struk extends Model
{
    protected $fillable = [
        'nama_toko',
        'nomor_struk',
        'tanggal_struk',
        'items',
        'total_harga',
        'foto_struk'
    ];
}
