<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'master_barang';

    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'jumlah',
        'status', // jangan lupa tambahkan status biar bisa diisi juga
    ];
}
