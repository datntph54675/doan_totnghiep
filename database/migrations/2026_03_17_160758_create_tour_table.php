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
        Schema::create('tour', function (Blueprint $table) {
            $table->id('tour_id');

            $table->unsignedBigInteger('category_id')->nullable();

            $table->string('name');
            $table->text('description')->nullable();
            $table->text('policy')->nullable();
            $table->string('supplier')->nullable();
            $table->string('image')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->decimal('price', 15, 2)->default(0);

            // FK
            $table->foreign('category_id')->references('category_id')->on('category')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour');
    }
};
