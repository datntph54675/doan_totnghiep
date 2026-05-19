<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            // Thêm cột date để xác định ngày điểm danh nếu chưa có
            if (!Schema::hasColumn('attendance', 'attendance_date')) {
                $table->date('attendance_date')->nullable()->after('guide_id');
            }
        });

        // Thêm unique, nếu đã có thì bỏ qua lỗi
        try {
            Schema::table('attendance', function (Blueprint $table) {
                $table->unique(
                    ['schedule_id', 'tour_customer_id', 'attendance_date'],
                    'unique_attendance_per_day'
                );
            });
        } catch (\Exception $e) {
            // Bỏ qua lỗi nếu unique đã tồn tại
        }

        // Backfill: lấy ngày từ marked_at cho các bản ghi cũ
        try {
            \Illuminate\Support\Facades\DB::statement(
                'UPDATE attendance SET attendance_date = DATE(marked_at) WHERE attendance_date IS NULL'
            );
        } catch (\Exception $e) {
            // Bỏ qua lỗi nếu đã cập nhật
        }
    }

    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropUnique('unique_attendance_per_day');
            $table->dropColumn('attendance_date');
        });
    }
};
