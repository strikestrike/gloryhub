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
        Schema::create('user_game_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('castle_level')->checkBetween(45,50);
            $table->integer('range_level')->checkBetween(45,50);
            $table->integer('stables_level')->checkBetween(45,50);
            $table->integer('barracks_level')->checkBetween(45,50);
            $table->integer('duke_badges');
            $table->date('last_updated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_game_data');
    }
};
