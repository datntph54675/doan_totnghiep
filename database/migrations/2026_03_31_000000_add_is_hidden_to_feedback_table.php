<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('content');
        });

        DB::table('feedback')
            ->where('type', 'an')
            ->update([
                'is_hidden' => true,
                'type' => 'danh_gia',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('feedback')
            ->where('is_hidden', true)
            ->update([
                'type' => 'an',
            ]);

        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn('is_hidden');
        });
    }
};
