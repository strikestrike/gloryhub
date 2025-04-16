<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('game_data', function (Blueprint $table) {
            $table->integer('target_level')->default(50);
            $table->string('target_building')->default('overall');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_data', function (Blueprint $table) {
            $table->dropColumn(['target_level', 'target_building']);
        });
    }
};
