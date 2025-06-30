<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            // Hapus kolom deskripsi jika ada
            if (Schema::hasColumn('pengeluarans', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            
            // Hapus kolom kategori jika ada
            if (Schema::hasColumn('pengeluarans', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            // Kembalikan kolom deskripsi untuk rollback
            if (!Schema::hasColumn('pengeluarans', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('tanggal');
            }
            
            // Kembalikan kolom kategori untuk rollback
            if (!Schema::hasColumn('pengeluarans', 'kategori')) {
                $table->string('kategori')->default('umum')->after('deskripsi');
            }
        });
    }
};