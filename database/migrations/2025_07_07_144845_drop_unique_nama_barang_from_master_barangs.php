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
            $table->dropUnique('master_barangs_nama_barang_unique');
        });
    }

    public function down()
    {
        Schema::table('master_barangs', function (Blueprint $table) {
            $table->unique('nama_barang');
        });
    }
};
