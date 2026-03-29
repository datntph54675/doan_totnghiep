<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('departure_schedule', function (Blueprint $table) {
            // Thêm cả max_people (sức chứa) và available_spots (chỗ trống hiện tại)
            if (!Schema::hasColumn('departure_schedule', 'max_people')) {
                $table->integer('max_people')->default(30)->after('end_date');
            }
            
            if (!Schema::hasColumn('departure_schedule', 'available_spots')) {
                $table->integer('available_spots')->default(30)->after('max_people');
            }
        });

        // Khởi tạo available_spots bằng max_people cho các bản ghi hiện có
        DB::table('departure_schedule')->whereNull('available_spots')->update([
            'available_spots' => DB::raw('max_people')
        ]);

        // Trừ đi số vé đã đặt (không tính đơn đã hủy) để có con số chính xác
        $schedules = DB::table('departure_schedule')->get();
        foreach ($schedules as $s) {
            $bookedCount = DB::table('booking')
                ->where('schedule_id', $s->schedule_id)
                ->where('status', '!=', 'cancelled')
                ->sum('num_people');
            
            DB::table('departure_schedule')
                ->where('schedule_id', $s->schedule_id)
                ->update(['available_spots' => $s->max_people - $bookedCount]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departure_schedule', function (Blueprint $table) {
            $table->dropColumn(['max_people', 'available_spots']);
        });
    }
};
