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
        try {
            Schema::table('attendance', function (Blueprint $table) {
                $table->dropUnique('attendance_unique_attendance_per_day');
            });
        } catch (\Exception $e) {
            // Bỏ qua nếu index không tồn tại với tên này.
        }

        try {
            Schema::table('attendance', function (Blueprint $table) {
                $table->dropUnique('unique_attendance_per_day');
            });
        } catch (\Exception $e) {
            // Bỏ qua nếu index đã được xóa.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->unique(['schedule_id', 'tour_customer_id', 'attendance_date'], 'attendance_unique_attendance_per_day');
        });
    }
};
