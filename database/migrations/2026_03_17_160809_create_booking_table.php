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
        Schema::create('booking', function (Blueprint $table) {
            $table->id('booking_id');

            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('tour_id');
            $table->unsignedBigInteger('schedule_id');

            $table->dateTime('booking_date')->useCurrent();
            $table->integer('num_people');
            $table->decimal('total_price', 10, 2)->default(0);

            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->enum('payment_status', ['unpaid', 'deposit', 'paid'])->default('unpaid');
            $table->text('note')->nullable();

            // FK
            $table->foreign('customer_id')->references('customer_id')->on('customer')->cascadeOnDelete();
            $table->foreign('tour_id')->references('tour_id')->on('tour')->cascadeOnDelete();
            $table->foreign('schedule_id')->references('schedule_id')->on('departure_schedule')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
