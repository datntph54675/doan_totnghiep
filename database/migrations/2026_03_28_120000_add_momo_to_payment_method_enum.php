<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE booking MODIFY COLUMN payment_method ENUM('vnpay', 'vietqr', 'momo') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE booking MODIFY COLUMN payment_method ENUM('vnpay', 'vietqr') NULL");
    }
};
