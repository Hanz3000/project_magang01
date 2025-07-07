<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('master_barangs', function (Blueprint $table) {
            $table->string('kode_barang')->nullable()->after('nama_barang'); // Izinkan null dulu
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_barangs', function (Blueprint $table) {
            //
        });
    }
};
