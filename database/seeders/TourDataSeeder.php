<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo Danh mục (Categories)
        $categories = [
            'Tour Miền Bắc' => 'Hành trình di sản và văn hóa ngàn năm',
            'Tour Miền Trung' => 'Biển xanh cát trắng nắng vàng',
            'Tour Miền Nam' => 'Trải nghiệm sông nước miền Tây',
            'Tour Biển Đảo' => 'Thiên đường rực rỡ nắng nhiệt đới',
            'Tour Sinh Thái' => 'Khám phá thiên nhiên hoang sơ',
            'Tour Quốc Tế' => 'Vươn tầm thế giới',
        ];

        $catIds = [];
        foreach ($categories as $name => $desc) {
            $catIds[$name] = DB::table('categories')->insertGetId([
                'name' => $name,
                'description' => $desc,
                'status' => 'active', // Thêm trường status mới được thêm ở file migration 2026_03_18
            ]);
        }

        // 2. Tạo 15 Tours mẫu
        $tours = [
            // Miền Bắc
            [
                'category_id' => $catIds['Tour Miền Bắc'], 'name' => 'Hà Nội - Hạ Long - Sapa 5N4Đ',
                'description' => 'Khám phá vẻ đẹp di sản Vịnh Hạ Long và đỉnh Fansipan Sapa mờ sương. Trọn gói vé tham quan, khách sạn 4 sao.',
                'policy' => 'Hoàn 100% nếu hủy trước 7 ngày. Phụ thu phòng đơn: 1.500.000 VNĐ.',
                'supplier' => 'VietTour', 'price' => 5500000, 'max_people' => 25, 'duration' => 5,
            ],
            [
                'category_id' => $catIds['Tour Miền Bắc'], 'name' => 'Mộc Châu - Mai Châu 2N1Đ - Mùa Hoa Mận',
                'description' => 'Săn mây, ngắm hoa mận trắng xóa đồi Mộc Châu. Trải nghiệm ngủ nhà sàn Mai Châu.',
                'policy' => 'Hủy trước 3 ngày mất 50%.',
                'supplier' => 'Saigontourist', 'price' => 1850000, 'max_people' => 15, 'duration' => 2,
            ],
            [
                'category_id' => $catIds['Tour Miền Bắc'], 'name' => 'Hà Giang - Mã Pì Lèng - Đồng Văn 3N2Đ',
                'description' => 'Chinh phục cực Bắc Tổ quốc. Ngắm hoa tam giác mạch và vượt đèo Mã Pì Lèng huyền thoại.',
                'policy' => 'Không áp dụng hoàn hủy dịp Lễ Tết.',
                'supplier' => 'Vietravel', 'price' => 2750000, 'max_people' => 20, 'duration' => 3,
            ],
            [
                'category_id' => $catIds['Tour Miền Bắc'], 'name' => 'Ninh Bình - Tràng An - Bái Đính 1 Ngày',
                'description' => 'Tour trong ngày khám phá Tràng An non nước hữu tình và chùa Bái Đính đồ sộ.',
                'policy' => 'Miễn phí hủy trước 24h.',
                'supplier' => 'VietTour', 'price' => 850000, 'max_people' => 40, 'duration' => 1,
            ],

            // Miền Trung
            [
                'category_id' => $catIds['Tour Miền Trung'], 'name' => 'Đà Nẵng - Hội An - Bà Nà 4N3Đ',
                'description' => 'Trọn vẹn miền Trung: Phố cổ Hội An lung linh, Bà Nà Hills bồng lai tiên cảnh và biển Đà Nẵng.',
                'policy' => 'Đã bao gồm vé cáp treo Bà Nà. Hủy mất cọc.',
                'supplier' => 'VietTour', 'price' => 6250000, 'max_people' => 30, 'duration' => 4,
            ],
            [
                'category_id' => $catIds['Tour Miền Trung'], 'name' => 'Huế - Lăng tẩm - Cố Đô 3N2Đ',
                'description' => 'Thưởng thức Nhã nhạc cung đình, thăm Đại Nội và các lăng tẩm hoàng gia ấn tượng.',
                'policy' => 'Hoàn 100% nếu hủy trước 5 ngày.',
                'supplier' => 'Fiditour', 'price' => 3400000, 'max_people' => 25, 'duration' => 3,
            ],
            [
                'category_id' => $catIds['Tour Miền Trung'], 'name' => 'Quy Nhơn - Kỳ Co - Eo Gió 3N2Đ',
                'description' => 'Khám phá Maldives phiên bản Việt Nam. Lặn ngắm san hô, check-in Eo Gió tuyệt đẹp.',
                'policy' => 'Không bao gồm chi phí lặn ngắm san hô (tự túc).',
                'supplier' => 'Vietravel', 'price' => 3950000, 'max_people' => 20, 'duration' => 3,
            ],

            // Miền Nam
            [
                'category_id' => $catIds['Tour Miền Nam'], 'name' => 'Miền Tây - Mỹ Tho - Bến Tre - Cần Thơ 3N2Đ',
                'description' => 'Hòa mình vào không khí Chợ Nổi Cái Răng, thưởng thức đờn ca tài tử và kẹo dừa Bến Tre.',
                'policy' => 'Hủy trước 3 ngày phạt 30%.',
                'supplier' => 'Saigontourist', 'price' => 2100000, 'max_people' => 35, 'duration' => 3,
            ],
            [
                'category_id' => $catIds['Tour Miền Nam'], 'name' => 'Khám phá Sài Gòn 1 Ngày',
                'description' => 'Tham quan Dinh Độc Lập, Bưu điện Trung tâm, và dạo thuyền trên sông Sài Gòn tối.',
                'policy' => 'Hủy trước 24h hoàn tiền 100%.',
                'supplier' => 'VietTour', 'price' => 750000, 'max_people' => 40, 'duration' => 1,
            ],
            [
                'category_id' => $catIds['Tour Miền Nam'], 'name' => 'Tây Ninh - Núi Bà Đen 1 Ngày',
                'description' => 'Hành hương đỉnh núi Bà Đen bằng hệ thống cáp treo hiện đại lớn nhất Đông Nam Bộ.',
                'policy' => 'Chưa bao gồm ăn trưa buffet (phụ thu 300k).',
                'supplier' => 'Vietravel', 'price' => 950000, 'max_people' => 45, 'duration' => 1,
            ],

            // Biển Đảo (hot)
            [
                'category_id' => $catIds['Tour Biển Đảo'], 'name' => 'Phú Quốc - Grand World - Hòn Thơm 4N3Đ',
                'description' => 'Nghỉ dưỡng trọn gói tại Đảo Ngọc. Trải nghiệm cáp treo Hòn Thơm và Thành phố không ngủ.',
                'policy' => 'Đã gồm vé máy bay khứ hồi từ HN/HCM. Không hoàn hủy.',
                'supplier' => 'VietTour', 'price' => 8900000, 'max_people' => 30, 'duration' => 4,
            ],
            [
                'category_id' => $catIds['Tour Biển Đảo'], 'name' => 'Nha Trang - VinWonders - Đảo Yến 3N2Đ',
                'description' => 'Vui chơi thả ga tại VinWonders, khám phá bãi tắm đôi lạ mắt tại Đảo Yến Sơn Hải.',
                'policy' => 'Phụ thu phòng đơn 1.2 triệu.',
                'supplier' => 'Fiditour', 'price' => 4800000, 'max_people' => 25, 'duration' => 3,
            ],
            [
                'category_id' => $catIds['Tour Biển Đảo'], 'name' => 'Côn Đảo - Tâm Linh - Lặn Biển 3N2Đ',
                'description' => 'Hành trình ý nghĩa thăm mộ Cô Sáu, nhà tù Côn Đảo kết hợp lặn ngắm tuyệt tác rạn san hô.',
                'policy' => 'Yêu cầu mặc trang phục lịch sự khi viếng mộ.',
                'supplier' => 'Vietravel', 'price' => 6500000, 'max_people' => 15, 'duration' => 3,
            ],

            // Sinh Thái & Quốc Tế
            [
                'category_id' => $catIds['Tour Sinh Thái'], 'name' => 'Trekking Tà Năng - Phan Dũng 2N1Đ',
                'description' => 'Cung đường trekking đẹp nhất Việt Nam đi qua 3 tỉnh Lâm Đồng - Ninh Thuận - Bình Thuận.',
                'policy' => 'Yêu cầu khách có thể lực tốt. Không nhận trẻ dưới 16 tuổi.',
                'supplier' => 'VietTour', 'price' => 2800000, 'max_people' => 15, 'duration' => 2,
            ],
            [
                'category_id' => $catIds['Tour Quốc Tế'], 'name' => 'Thái Lan - Bangkok - Pattaya 5N4Đ',
                'description' => 'Trải nghiệm văn hóa, mua sắm và show diễn Alcazar hoành tráng ở xứ chùa Vàng.',
                'policy' => 'Đã gồm VMB và khách sạn 4 sao thái lan. Yêu cầu Passport còn hạn 6 tháng.',
                'supplier' => 'Vietravel', 'price' => 7900000, 'max_people' => 25, 'duration' => 5,
            ]
        ];

        foreach ($tours as $t) {
            try {
                $t['status'] = 'active';
                // max_people đã được chuyển sang bảng departure_schedule nên cần xóa khỏi array insert tours
                unset($t['max_people']);
                DB::table('tours')->insert($t);
            } catch (\Exception $e) {
                echo "Error inserting tour '{$t['name']}': " . $e->getMessage() . "\n";
            }
        }

        // Tạo 10 Customer / User ngẫu nhiên nếu chưa có đủ
        for ($i = 1; $i <= 10; $i++) {
            try {
                DB::table('customer')->insert([
                    'fullname' => 'Khách hàng số ' . $i,
                    'email' => 'khachhang'.$i.'@example.com',
                    'phone' => '09010000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'id_number' => '0010020030' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'gender' => $i % 2 == 0 ? 'Nữ' : 'Nam',
                ]);
            } catch (\Exception $e) {
                echo "Error inserting customer $i: " . $e->getMessage() . "\n";
            }
        }
    }
}
