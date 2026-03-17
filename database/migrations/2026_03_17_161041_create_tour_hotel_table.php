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
        Schema::create('tour_hotel', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tour_id');
            $table->unsignedBigInteger('hotel_id');

            $table->string('location');

            // FK
            $table->foreign('tour_id')->references('tour_id')->on('tour')->cascadeOnDelete();
            $table->foreign('hotel_id')->references('hotel_id')->on('hotel')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_hotel');
    }
};
