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
        Schema::create('tour_customer', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('customer_id');

            $table->string('room_number')->nullable();
            $table->enum('checkin_status', ['not_checked_in', 'checked_in'])->default('not_checked_in');
            $table->text('note')->nullable();
            $table->enum('attendance_status', ['present', 'absent', 'unknown'])->default('unknown');

            // FK
            $table->foreign('schedule_id')->references('schedule_id')->on('departure_schedule')->cascadeOnDelete();
            $table->foreign('customer_id')->references('customer_id')->on('customer')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_customer');
    }
};
