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
        Schema::create('departure_schedule', function (Blueprint $table) {
            $table->id('schedule_id');

            $table->unsignedBigInteger('tour_id');
            $table->date('start_date');
            $table->date('end_date');

            $table->string('meeting_point')->nullable();

            $table->unsignedBigInteger('guide_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('hotel_id')->nullable();

            $table->text('notes')->nullable();

            // FK
            $table->foreign('tour_id')->references('tour_id')->on('tour')->cascadeOnDelete();
            $table->foreign('guide_id')->references('guide_id')->on('guide')->nullOnDelete();
            $table->foreign('driver_id')->references('driver_id')->on('driver')->nullOnDelete();
            $table->foreign('hotel_id')->references('hotel_id')->on('hotel')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departure_schedule');
    }
};
