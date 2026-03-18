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
        Schema::create('guide', function (Blueprint $table) {
            $table->id('guide_id');

            $table->unsignedBigInteger('user_id');

            $table->string('cccd', 20)->nullable();
            $table->string('language')->nullable();
            $table->string('certificate')->nullable();
            $table->text('experience')->nullable();
            $table->string('specialization')->nullable();

            // FK
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide');
    }
};
