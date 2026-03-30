<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE feedback MODIFY COLUMN type ENUM('danh_gia', 'su_co', 'an') DEFAULT 'danh_gia'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE feedback MODIFY COLUMN type ENUM('danh_gia', 'su_co') DEFAULT 'danh_gia'");
    }
};