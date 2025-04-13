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
        Schema::create('kingdoms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subscription_level');
            $table->unsignedBigInteger('current_king_id')->nullable();

            $table->timestamps();

            $table->foreign('current_king_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kingdoms');
    }
};
