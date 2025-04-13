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
        Schema::create('alliances', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('kingdom_id');
            $table->unsignedBigInteger('r5_user_id')->nullable();
            $table->timestamps();

            $table->foreign('kingdom_id')->references('id')->on('kingdoms')->onDelete('cascade');
            $table->foreign('r5_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alliances');
    }
};
