<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_customer', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->nullable()->after('customer_id');
            $table->foreign('booking_id')->references('booking_id')->on('booking')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tour_customer', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropColumn('booking_id');
        });
    }
};
