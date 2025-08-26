<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah status di struks
        Schema::table('struks', function (Blueprint $table) {
            if (!Schema::hasColumn('struks', 'status')) {
                $table->enum('status', ['progress', 'complete'])->default('progress')->after('tanggal');
            }
        });

        // Tambah struk_id di pengeluarans
        Schema::table('pengeluarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengeluarans', 'struk_id')) {
                $table->foreignId('struk_id')->nullable()->after('id')->constrained('struks')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropForeign(['struk_id']);
            $table->dropColumn('struk_id');
        });

        Schema::table('struks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};