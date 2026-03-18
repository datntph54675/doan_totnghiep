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
        Schema::create('driver', function (Blueprint $table) {
            $table->id('driver_id');
            $table->string('fullname', 100);
            $table->string('phone', 20)->nullable();
            $table->string('license_plate', 20)->nullable();
            $table->string('vehicle_type', 50)->nullable();
            $table->enum('status', ['available', 'busy'])->default('available');
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver');
    }
};
