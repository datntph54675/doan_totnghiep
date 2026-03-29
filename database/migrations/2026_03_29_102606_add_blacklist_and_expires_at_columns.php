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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_blacklisted')) {
                $table->boolean('is_blacklisted')->default(false)->after('status');
            }
        });

        Schema::table('booking', function (Blueprint $table) {
            if (!Schema::hasColumn('booking', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('payment_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_blacklisted');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
};
