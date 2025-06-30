<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
    $table->id();
    $table->string('nama_toko');
    $table->string('nomor_struk');
    $table->date('tanggal');
    $table->json('daftar_barang'); // untuk menyimpan array items
    $table->decimal('total', 12, 2);
    $table->integer('jumlah_item');
    $table->string('bukti_pembayaran')->nullable();
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('pengeluarans');
    }
};