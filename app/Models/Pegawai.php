<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Division;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nip',
        'user_id',
        'divisi_id',
    ];

    /**
     * Relasi: Pegawai dimiliki oleh satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Pegawai dimiliki oleh satu Divisi
     */
    public function divisi()
    {
        return $this->belongsTo(Division::class, 'divisi_id');
    }

    /**
     * Relasi: Pegawai memiliki banyak Pengeluaran
     */
    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }
}
