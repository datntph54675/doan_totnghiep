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
        Schema::table('booking', function (Blueprint $table) {
            if (!Schema::hasColumn('booking', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('booking', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });

        // Đồng bộ dữ liệu cũ: Lấy booking_date làm mặc định cho created_at và updated_at
        DB::table('booking')->whereNull('created_at')->update([
            'created_at' => DB::raw('booking_date'),
            'updated_at' => DB::raw('booking_date'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};
