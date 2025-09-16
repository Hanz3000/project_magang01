<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('struks', function (Blueprint $table) {
            $table->string('status')
                  ->default('progress')
                  ->check("status IN ('progress','completed')");
        });
    }

    public function down()
    {
        Schema::table('struks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
