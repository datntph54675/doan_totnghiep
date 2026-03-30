<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guide_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guide_id');
            $table->enum('type', ['danh_gia', 'su_co'])->default('danh_gia')->comment('danh_gia: Đánh giá hệ thống, su_co: Báo cáo sự cố');
            $table->string('title')->comment('Tiêu đề phản hồi');
            $table->text('content')->comment('Nội dung chi tiết');
            $table->enum('status', ['pending', 'viewed', 'resolved'])->default('pending')->comment('pending: Chưa xem, viewed: Đã xem, resolved: Đã xử lý');
            $table->text('admin_reply')->nullable()->comment('Phản hồi từ admin');
            $table->timestamps();

            // Foreign key
            $table->foreign('guide_id')
                ->references('guide_id')
                ->on('guide')
                ->cascadeOnDelete();

            // Indexes
            $table->index('guide_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_feedback');
    }
};
