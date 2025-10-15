<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus constraint lama
        DB::statement('ALTER TABLE struks DROP CONSTRAINT IF EXISTS struks_status_check');

        // Tambahkan constraint baru (progress, completed)
        DB::statement("ALTER TABLE struks 
            ADD CONSTRAINT struks_status_check 
            CHECK (status IN ('progress', 'completed'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback ke constraint lama (progress, selesai)
        DB::statement('ALTER TABLE struks DROP CONSTRAINT IF EXISTS struks_status_check');

        DB::statement("ALTER TABLE struks 
            ADD CONSTRAINT struks_status_check 
            CHECK (status IN ('progress', 'selesai'))");
    }
};
