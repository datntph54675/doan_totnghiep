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
        // Update foreign keys to reference 'tours' instead of 'tour'
        Schema::table('departure_schedule', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->foreign('tour_id')->references('tour_id')->on('tours')->cascadeOnDelete();
        });
        Schema::table('booking', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->foreign('tour_id')->references('tour_id')->on('tours')->cascadeOnDelete();
        });
        Schema::table('tour_hotel', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->foreign('tour_id')->references('tour_id')->on('tours')->cascadeOnDelete();
        });
        Schema::table('itinerary', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->foreign('tour_id')->references('tour_id')->on('tours')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert foreign keys to reference 'tour'

        Schema::table('departure_schedule', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->foreign('tour_id')->references('tour_id')->on('tour')->cascadeOnDelete();
        });
        Schema::table('booking', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->foreign('tour_id')->references('tour_id')->on('tour')->cascadeOnDelete();
        });
        Schema::table('tour_hotel', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->foreign('tour_id')->references('tour_id')->on('tour')->cascadeOnDelete();
        });
        Schema::table('itinerary', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->foreign('tour_id')->references('tour_id')->on('tour')->cascadeOnDelete();
        });
    }
};
