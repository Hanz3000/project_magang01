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
        Schema::create('struks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');
            $table->string('nomor_struk');
            $table->date('tanggal_struk');
            $table->json('items');
            $table->integer('total_harga');
            $table->string('foto_struk')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('struks', function (Blueprint $table) {
            $table->dropColumn([
                'nama_toko',
                'nomor_struk',
                'tanggal_struk',
                'items',
                'total_harga',
                'foto_struk',
            ]);
        });
    }
};
