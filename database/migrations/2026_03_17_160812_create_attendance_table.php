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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id('attendance_id');

            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('tour_customer_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('guide_id');

            $table->enum('status', ['present', 'absent', 'unknown'])->default('unknown');
            $table->text('note')->nullable();
            $table->dateTime('marked_at')->useCurrent();

            // FK
            $table->foreign('schedule_id')->references('schedule_id')->on('departure_schedule')->cascadeOnDelete();
            $table->foreign('customer_id')->references('customer_id')->on('customer')->cascadeOnDelete();
            $table->foreign('guide_id')->references('guide_id')->on('guide')->cascadeOnDelete();
            $table->foreign('tour_customer_id')->references('id')->on('tour_customer')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
