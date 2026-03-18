<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourDataSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo category (cột: name)
        $categoryId = DB::table('category')->insertGetId([
            'name' => 'Tour Miền Bắc',
            'description' => 'Các tour du lịch miền Bắc Việt Nam',
        ]);

        // Tạo tour (cột: name, không có tour_name)
        $tourId = DB::table('tour')->insertGetId([
            'category_id' => $categoryId,
            'name' => 'Hà Nội - Hạ Long - Sapa 5N4Đ',
            'description' => 'Khám phá vẻ đẹp miền Bắc với Vịnh Hạ Long và Sapa',
            'policy' => 'Hoàn tiền 100% nếu hủy trước 7 ngày',
            'supplier' => 'VietTravel',
            'price' => 5500000,
            'max_people' => 30,
            'duration' => 5,
            'status' => 'active',
        ]);

        // Tạo itinerary (cột: itinerary_id, time_start, time_end)
        $itineraries = [
            ['tour_id' => $tourId, 'day_number' => 1, 'title' => 'Hà Nội - Khởi hành đi Hạ Long', 'description' => 'Xe đón tại điểm hẹn, khởi hành đi Hạ Long. Tham quan Vịnh Hạ Long, thưởng thức hải sản tươi sống.', 'location' => 'Hạ Long, Quảng Ninh'],
            ['tour_id' => $tourId, 'day_number' => 2, 'title' => 'Khám phá Vịnh Hạ Long', 'description' => 'Du thuyền trên vịnh, tham quan hang Sửng Sốt, làng chài, chèo kayak.', 'location' => 'Vịnh Hạ Long'],
            ['tour_id' => $tourId, 'day_number' => 3, 'title' => 'Hạ Long - Hà Nội - Sapa', 'description' => 'Về Hà Nội, khởi hành đi Sapa. Nghỉ đêm tại Sapa.', 'location' => 'Sapa, Lào Cai'],
            ['tour_id' => $tourId, 'day_number' => 4, 'title' => 'Trekking Sapa - Thác Bạc', 'description' => 'Trekking qua các bản làng, thăm thác Bạc, tìm hiểu văn hóa dân tộc.', 'location' => 'Sapa, Lào Cai'],
            ['tour_id' => $tourId, 'day_number' => 5, 'title' => 'Sapa - Hà Nội - Kết thúc', 'description' => 'Tham quan chợ Sapa, mua sắm đặc sản. Về Hà Nội, kết thúc chuyến đi.', 'location' => 'Hà Nội'],
        ];
        foreach ($itineraries as $item) {
            DB::table('itinerary')->insert($item);
        }

        $guideId = DB::table('guide')->first()->guide_id ?? null;
        if (!$guideId) return;

        // Tạo lịch khởi hành sắp tới
        $scheduleId1 = DB::table('departure_schedule')->insertGetId([
            'tour_id' => $tourId,
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addDays(10)->format('Y-m-d'),
            'meeting_point' => 'Số 1 Bà Triệu, Hoàn Kiếm, Hà Nội',
            'guide_id' => $guideId,
            'notes' => 'Khách mang theo CCCD và giấy tờ cần thiết',
            'status' => 'scheduled',
        ]);

        // Tạo lịch đang diễn ra
        $scheduleId2 = DB::table('departure_schedule')->insertGetId([
            'tour_id' => $tourId,
            'start_date' => now()->subDays(2)->format('Y-m-d'),
            'end_date' => now()->addDays(3)->format('Y-m-d'),
            'meeting_point' => 'Số 1 Bà Triệu, Hoàn Kiếm, Hà Nội',
            'guide_id' => $guideId,
            'status' => 'ongoing',
        ]);

        // Tạo khách hàng mẫu (cột: id_number, không có address/cccd)
        $customers = [
            ['fullname' => 'Trần Văn An',   'email' => 'an.tran@example.com',   'phone' => '0901234567', 'id_number' => '001234567891', 'gender' => 'Nam'],
            ['fullname' => 'Lê Thị Bình',   'email' => 'binh.le@example.com',   'phone' => '0902234567', 'id_number' => '001234567892', 'gender' => 'Nữ'],
            ['fullname' => 'Phạm Minh Châu', 'email' => 'chau.pham@example.com', 'phone' => '0903234567', 'id_number' => '001234567893', 'gender' => 'Nam'],
        ];

        foreach ($customers as $customer) {
            $customerId = DB::table('customer')->insertGetId($customer);

            // booking cần: customer_id, tour_id, schedule_id, num_people, total_price, status, payment_status
            $bookingId1 = DB::table('booking')->insertGetId([
                'customer_id'    => $customerId,
                'tour_id'        => $tourId,
                'schedule_id'    => $scheduleId1,
                'num_people'     => 1,
                'total_price'    => 5500000,
                'status'         => 'upcoming',
                'payment_status' => 'paid',
            ]);

            $bookingId2 = DB::table('booking')->insertGetId([
                'customer_id'    => $customerId,
                'tour_id'        => $tourId,
                'schedule_id'    => $scheduleId2,
                'num_people'     => 1,
                'total_price'    => 5500000,
                'status'         => 'ongoing',
                'payment_status' => 'paid',
            ]);

            // Feedback mẫu
            if ($customerId % 2 == 0) {
                DB::table('feedback')->insert([
                    'booking_id' => $bookingId2,
                    'type'       => 'danh_gia',
                    'rating'     => rand(4, 5),
                    'content'    => 'Tour rất tuyệt vời, hướng dẫn viên nhiệt tình!',
                    'created_at' => now()->subDays(1),
                ]);
            }
        }
    }
}
