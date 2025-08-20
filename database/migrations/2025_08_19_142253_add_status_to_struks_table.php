<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('master_barang', function (Blueprint $table) {
            $table->enum('status', ['progress', 'completed'])->default('progress')->after('nama_barang');
        });
    }

    public function down(): void
    {
        Schema::table('master_barang', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
