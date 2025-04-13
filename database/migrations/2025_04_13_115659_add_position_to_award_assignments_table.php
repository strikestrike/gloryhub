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
        Schema::table('award_assignments', function (Blueprint $table) {
            $table->unsignedTinyInteger('position')->default(0);
            $table->string('kingdom_level');
            $table->unique(['type', 'kingdom_level', 'position'], 'unique_award_slot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('award_assignments', function (Blueprint $table) {
            $table->dropUnique('unique_award_slot');
            $table->dropColumn('kingdom_level');
            $table->dropColumn('position');
        });
    }
};
