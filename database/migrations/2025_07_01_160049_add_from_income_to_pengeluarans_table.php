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
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->boolean('from_income')->default(false)->after('bukti_pembayaran');
            $table->foreignId('struk_id')->nullable()->constrained('struks')->after('from_income');
        });
    }

    public function down()
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropForeign(['struk_id']);
            $table->dropColumn(['from_income', 'struk_id']);
        });
    }
};
