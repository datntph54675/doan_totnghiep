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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('booking_id');

            $table->enum('type', ['danh_gia', 'su_co'])->default('danh_gia');
            $table->integer('rating')->nullable()->comment('Rating từ 1-5');
            $table->text('content')->nullable();
            $table->dateTime('created_at')->useCurrent();

            // FK
            $table->foreign('booking_id')->references('booking_id')->on('booking')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
