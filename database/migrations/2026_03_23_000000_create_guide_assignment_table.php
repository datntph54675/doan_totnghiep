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
        Schema::create('guide_assignment', function (Blueprint $table) {
            $table->id('id');

            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('guide_id');

            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->timestamp('assigned_at')->useCurrent();
            $table->enum('status', ['active', 'cancelled', 'completed'])->default('active');
            $table->text('note')->nullable();

            $table->timestamps();

            $table->foreign('schedule_id')
                ->references('schedule_id')
                ->on('departure_schedule')
                ->cascadeOnDelete();

            $table->foreign('guide_id')
                ->references('guide_id')
                ->on('guide')
                ->cascadeOnDelete();

            $table->foreign('assigned_by')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete();

            $table->unique(['schedule_id', 'guide_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_assignment');
    }
};
