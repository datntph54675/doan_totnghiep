<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('departure_schedule', function (Blueprint $table) {
            if (!Schema::hasColumn('departure_schedule', 'max_people')) {
                $table->integer('max_people')->default(0)->after('end_date');
            }
        });

        // Migrate existing tour-level data into departure_schedule
        $tours = DB::table('tours')->select('tour_id', 'start_date', 'end_date', 'max_people')->get();

        foreach ($tours as $tour) {
            if ($tour->start_date && $tour->end_date) {
                $exists = DB::table('departure_schedule')
                    ->where('tour_id', $tour->tour_id)
                    ->where('start_date', $tour->start_date)
                    ->where('end_date', $tour->end_date)
                    ->exists();

                if (!$exists) {
                    DB::table('departure_schedule')->insert([
                        'tour_id' => $tour->tour_id,
                        'start_date' => $tour->start_date,
                        'end_date' => $tour->end_date,
                        'max_people' => $tour->max_people ?? 0,
                        'status' => 'scheduled',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Remove redundant columns from tours
        Schema::table('tours', function (Blueprint $table) {
            if (Schema::hasColumn('tours', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('tours', 'end_date')) {
                $table->dropColumn('end_date');
            }
            if (Schema::hasColumn('tours', 'max_people')) {
                $table->dropColumn('max_people');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            if (!Schema::hasColumn('tours', 'max_people')) {
                $table->integer('max_people')->default(0);
            }
            if (!Schema::hasColumn('tours', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('tours', 'end_date')) {
                $table->date('end_date')->nullable();
            }
        });

        // Copy sample data back from departure_schedule to tours (first schedule)
        $schedules = DB::table('departure_schedule')
            ->select('tour_id', 'start_date', 'end_date', 'max_people')
            ->orderBy('schedule_id')
            ->get();

        foreach ($schedules as $schedule) {
            DB::table('tours')
                ->where('tour_id', $schedule->tour_id)
                ->update([
                    'start_date' => $schedule->start_date,
                    'end_date' => $schedule->end_date,
                    'max_people' => $schedule->max_people,
                ]);
        }

        if (Schema::hasColumn('departure_schedule', 'max_people')) {
            Schema::table('departure_schedule', function (Blueprint $table) {
                $table->dropColumn('max_people');
            });
        }
    }
};
