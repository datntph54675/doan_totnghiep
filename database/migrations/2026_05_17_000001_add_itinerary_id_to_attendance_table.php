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
        Schema::table('attendance', function (Blueprint $table) {
            $table->unsignedBigInteger('itinerary_id')->nullable()->after('schedule_id');
            $table->foreign('itinerary_id')->references('itinerary_id')->on('itinerary')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropForeign(['itinerary_id']);
            $table->dropColumn('itinerary_id');
        });
    }
};
