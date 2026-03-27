<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TourDetailSeeder extends Seeder
{
    public function run(): void
    {
        $tours = DB::table('tours')->get();

        foreach ($tours as $tour) {
            // 1. Tạo Lịch khởi hành (2 chuyến cho mỗi tour)
            DB::table('departure_schedule')->insert([
                [
                    'tour_id' => $tour->tour_id,
                    'start_date' => Carbon::now()->addDays(rand(7, 15)),
                    'end_date' => Carbon::now()->addDays(rand(16, 20)),
                    'max_people' => 20,
                    'status' => 'scheduled',
                    'meeting_point' => 'Văn phòng VietTour - Hà Nội',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'tour_id' => $tour->tour_id,
                    'start_date' => Carbon::now()->addDays(rand(21, 30)),
                    'end_date' => Carbon::now()->addDays(rand(31, 35)),
                    'max_people' => 25,
                    'status' => 'scheduled',
                    'meeting_point' => 'Sân bay Nội Bài/Tân Sơn Nhất',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            // 2. Tạo Lịch trình (Itinerary) - Số ngày dựa trên duration của tour
            $duration = $tour->duration ?: 3;
            $itineraries = [];
            for ($i = 1; $i <= $duration; $i++) {
                $itineraries[] = [
                    'tour_id' => $tour->tour_id,
                    'day_number' => $i,
                    'title' => "Khám phá ngày thứ $i",
                    'description' => "Lịch trình chi tiết cho ngày $i: Tham quan các địa danh nổi tiếng, thưởng thức ẩm thực địa phương và nghỉ ngơi tại khách sạn tiêu chuẩn.",
                ];
            }
            DB::table('itinerary')->insert($itineraries);
        }
    }
}
