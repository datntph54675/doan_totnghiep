# Giao diện Hướng dẫn viên (HDV)

## Tổng quan
Giao diện HDV được xây dựng đầy đủ để giúp hướng dẫn viên quản lý các tour được phân công, xem thông tin khách hàng, điểm danh, xem lịch trình và feedback.

## Các tính năng đã triển khai ✅

### 1. Dashboard HDV
- ✅ Hiển thị thống kê: Tour sắp tới, đang diễn ra, đã hoàn thành
- ✅ Danh sách tour được phân công theo trạng thái
- ✅ Thông tin cơ bản về mỗi tour (tên, ngày, số khách, điểm tập trung)
- ✅ Giao diện responsive, hiện đại

### 2. Chi tiết Tour
- ✅ Thông tin đầy đủ về tour (ngày khởi hành, kết thúc, điểm tập trung)
- ✅ Danh sách khách hàng đăng ký (họ tên, email, SĐT, CCCD)
- ✅ Trạng thái booking của từng khách
- ✅ Mô tả tour
- ✅ **Lịch trình chi tiết (Itinerary)** - Hiển thị lịch trình theo từng ngày
- ✅ **Đánh giá từ khách hàng (Feedback)** - Xem rating và nhận xét
- ✅ Link đến trang điểm danh

### 3. Điểm danh khách hàng (Attendance) ⭐
- ✅ Danh sách khách hàng cần điểm danh
- ✅ Đánh dấu trạng thái: Có mặt / Vắng mặt / Chưa rõ
- ✅ Thêm ghi chú cho mỗi lần điểm danh
- ✅ Xem lịch sử điểm danh
- ✅ Hiển thị trạng thái điểm danh gần nhất
- ✅ Modal form điểm danh tiện lợi

### 4. Quản lý lịch trình (Itinerary Management) ⭐ MỚI
- ✅ Xem lịch trình chi tiết theo từng ngày
- ✅ **Cập nhật thông tin lịch trình** (tiêu đề, mô tả, địa điểm)
- ✅ Modal form chỉnh sửa tiện lợi
- ✅ Lưu thay đổi real-time

## Cấu trúc Files

### Models (Đầy đủ)
- `app/Models/Guide.php` - Model cho hướng dẫn viên
- `app/Models/DepartureSchedule.php` - Model cho lịch khởi hành
- `app/Models/Booking.php` - Model cho đơn đặt tour
- `app/Models/Customer.php` - Model cho khách hàng
- `app/Models/Attendance.php` ⭐ - Model cho điểm danh
- `app/Models/Itinerary.php` ⭐ - Model cho lịch trình
- `app/Models/Feedback.php` ⭐ - Model cho đánh giá

### Controllers
- `app/Http/Controllers/GuideController.php` - Controller xử lý logic cho HDV
  - `dashboard()` - Trang chủ HDV
  - `tourDetail()` - Chi tiết tour với itinerary và feedback
  - `attendance()` ⭐ - Trang điểm danh
  - `markAttendance()` ⭐ - Xử lý điểm danh
- `app/Http/Controllers/GuideAuthController.php` - Controller xử lý đăng nhập/đăng xuất

### Views
- `resources/views/guide/dashboard.blade.php` - Trang dashboard
- `resources/views/guide/tour-detail.blade.php` - Trang chi tiết tour (có itinerary & feedback)
- `resources/views/guide/attendance.blade.php` ⭐ - Trang điểm danh
- `resources/views/guide/itinerary.blade.php` ⭐ - Trang quản lý lịch trình
- `resources/views/guide/login.blade.php` - Trang đăng nhập

### Routes
```php
Route::prefix('guide')->group(function () {
    Route::get('login', [GuideAuthController::class, 'showLogin']);
    Route::post('login', [GuideAuthController::class, 'login']);
    
    Route::middleware(['auth', EnsureRole::class . ':tour_guide'])->group(function () {
        Route::post('logout', [GuideAuthController::class, 'logout']);
        Route::get('dashboard', [GuideController::class, 'dashboard']);
        Route::get('tour/{scheduleId}', [GuideController::class, 'tourDetail']);
        Route::get('tour/{scheduleId}/attendance', [GuideController::class, 'attendance']); ⭐
        Route::post('tour/{scheduleId}/attendance', [GuideController::class, 'markAttendance']); ⭐
        Route::get('tour/{scheduleId}/itinerary', [GuideController::class, 'itinerary']); ⭐
        Route::put('itinerary/{itineraryId}', [GuideController::class, 'updateItinerary']); ⭐
    });
});
```

## Cài đặt và chạy

### 1. Chạy migrations
```bash
php artisan migrate
```

### 2. Chạy seeders để tạo dữ liệu mẫu
```bash
php artisan db:seed
```

Seeder sẽ tạo:
- ✅ Tài khoản HDV: username `guide`, password `123456`
- ✅ Hồ sơ HDV với thông tin đầy đủ
- ✅ 1 tour mẫu "Hà Nội - Hạ Long - Sapa 5N4Đ"
- ✅ 5 lịch trình chi tiết (itinerary) theo ngày
- ✅ 2 lịch khởi hành (1 sắp tới, 1 đang diễn ra)
- ✅ 3 khách hàng mẫu với bookings
- ✅ Feedback mẫu từ khách hàng

### 3. Truy cập giao diện
- Đăng nhập: `http://localhost:8000/guide/login`
- Username: `guide`
- Password: `123456`

## So sánh với yêu cầu từ plan.md

### Yêu cầu: "Guide: xem assigned tours, cập nhật itinerary, attendance, feedback"

✅ **Xem assigned tours** - Dashboard hiển thị đầy đủ tour được phân công
✅ **Cập nhật itinerary** - Trang quản lý lịch trình với chức năng chỉnh sửa
✅ **Attendance** - Hệ thống điểm danh hoàn chỉnh
✅ **Feedback** - Xem đánh giá từ khách hàng

### ✅ ĐÃ ĐÁP ỨNG ĐẦY ĐỦ 100% YÊU CẦU!

### Đã đáp ứng đầy đủ:
✅ **Guide (Hướng dẫn viên)** - Xem thông tin cá nhân, tour được phân công
✅ **Departure Schedule** - Xem lịch khởi hành chi tiết
✅ **Booking** - Xem danh sách khách đặt tour
✅ **Customer** - Xem thông tin khách hàng
✅ **Attendance** - Điểm danh khách hàng
✅ **Itinerary** - Xem lịch trình chi tiết theo ngày
✅ **Feedback** - Xem đánh giá từ khách hàng

### Relationships đã implement:
- Guide → DepartureSchedule (1-N)
- DepartureSchedule → Tour (N-1)
- DepartureSchedule → Booking (1-N)
- Booking → Customer (N-1)
- Booking → Feedback (1-N)
- Tour → Itinerary (1-N)
- DepartureSchedule → Attendance (1-N)
- Attendance → Customer (N-1)
- Attendance → Guide (N-1)

## Tính năng nổi bật

### 1. Điểm danh thông minh
- Hiển thị trạng thái điểm danh gần nhất
- Modal form tiện lợi, không cần reload trang
- Lịch sử điểm danh đầy đủ
- Ghi chú chi tiết cho từng lần điểm danh

### 2. Lịch trình trực quan
- Hiển thị theo từng ngày
- Thông tin địa điểm, thời gian rõ ràng
- Dễ theo dõi tiến độ tour

### 3. Feedback từ khách
- Xem rating sao (1-5)
- Đọc nhận xét chi tiết
- Phân loại: Đánh giá / Sự cố

### 4. Quản lý lịch trình
- Xem lịch trình theo từng ngày
- **Cập nhật thông tin** (tiêu đề, mô tả, địa điểm)
- Modal form chỉnh sửa trực quan
- Lưu thay đổi ngay lập tức

## Các tính năng có thể mở rộng (Optional)

1. **Cập nhật tiến độ lịch trình**
   - Đánh dấu hoàn thành từng điểm trong itinerary
   - Ghi chú thay đổi lịch trình

2. **Báo cáo tour**
   - Tạo báo cáo sau khi hoàn thành tour
   - Ghi chú sự cố, vấn đề phát sinh
   - Export PDF

3. **Thông báo real-time**
   - Nhắc tour sắp khởi hành
   - Thông báo thay đổi lịch trình
   - Thông báo feedback mới

4. **Quản lý tài liệu**
   - Upload hình ảnh tour
   - Tài liệu hướng dẫn
   - Checklist chuẩn bị

5. **Chat với khách hàng**
   - Nhắn tin trực tiếp
   - Thông báo quan trọng

## Ghi chú kỹ thuật

- ✅ Sử dụng Eloquent ORM với relationships đầy đủ
- ✅ Middleware `EnsureRole` để bảo vệ routes
- ✅ Responsive design với CSS thuần, không cần framework
- ✅ Form validation đầy đủ
- ✅ Modal dialog với vanilla JavaScript
- ✅ Seeders tạo dữ liệu mẫu hoàn chỉnh

## Kết luận

Giao diện HDV đã được xây dựng **ĐẦY ĐỦ 100%** theo yêu cầu từ plan.md:

**Yêu cầu gốc:** "Guide: xem assigned tours, cập nhật itinerary, attendance, feedback"

✅ **Xem assigned tours** - Dashboard với thống kê và danh sách tour
✅ **Cập nhật itinerary** - Trang quản lý lịch trình với form chỉnh sửa
✅ **Attendance** - Hệ thống điểm danh hoàn chỉnh
✅ **Feedback** - Xem đánh giá từ khách hàng

Tất cả các chức năng cốt lõi cho HDV đã được triển khai hoàn chỉnh với giao diện đẹp, dễ sử dụng!
