<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            // Thêm cột date để xác định ngày điểm danh
            // Kết hợp (schedule_id, tour_customer_id, attendance_date) là unique
            // => mỗi khách chỉ có 1 bản ghi điểm danh mỗi ngày
            $table->date('attendance_date')->nullable()->after('guide_id');

            $table->unique(
                ['schedule_id', 'tour_customer_id', 'attendance_date'],
                'unique_attendance_per_day'
            );
        });

        // Backfill: lấy ngày từ marked_at cho các bản ghi cũ
        \Illuminate\Support\Facades\DB::statement(
            'UPDATE attendance SET attendance_date = DATE(marked_at) WHERE attendance_date IS NULL'
        );
    }

    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropUnique('unique_attendance_per_day');
            $table->dropColumn('attendance_date');
        });
    }
};
