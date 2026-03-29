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
        Schema::table('guide_assignment', function (Blueprint $table) {
            // Thêm cột lý do từ chối
            $table->text('rejection_reason')->nullable()->after('note');
            // Thêm cột ngày xác nhận
            $table->timestamp('confirmed_at')->nullable()->after('assigned_at');
            // Thay đổi status thành pending khi mới được gán
            $table->dropColumn('status');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'cancelled', 'completed'])->default('pending')->after('confirmed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guide_assignment', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'confirmed_at']);
            $table->dropColumn('status');
            $table->enum('status', ['active', 'cancelled', 'completed'])->default('active');
        });
    }
};
