# Schema Database - Website Đặt Tour Du Lịch

## Tổng quan các bảng dữ liệu

---

## 1. **users** - Người dùng hệ thống
**Lưu thông tin:**
- `user_id` (PK)
- `username` - Tên đăng nhập (unique)
- `password` - Mật khẩu
- `fullname` - Họ tên đầy đủ
- `email` - Email
- `phone` - Điện thoại
- `role` - Vai trò [admin, tour_guide, user]
- `status` - Trạng thái [active, inactive]
- `created_at`, `updated_at` - Thời gian

**Liên kết với:**
- guide (1-1): Nếu role = tour_guide
- driver (1-1): Nếu là tài xế
- booking (1-N): User đặt nhiều tour
- feedback (1-N): User để lại feedback
- tour_guides (N-N): Phân công hướng dẫn viên

---

## 2. **category** - Danh mục tour
**Lưu thông tin:**
- `category_id` (PK)
- `name` - Tên danh mục (100 ký tự)
- `description` - Mô tả

**Liên kết với:**
- tour (1-N): Một danh mục có nhiều tour

---

## 3. **customer** - Khách hàng
**Lưu thông tin:**
- `customer_id` (PK)
- `fullname` - Họ tên (100 ký tự)
- `gender` - Giới tính [Nam, Nữ, Khác]
- `birthdate` - Ngày sinh
- `phone` - Điện thoại
- `email` - Email
- `id_number` - Số CCCD/CMND
- `notes` - Ghi chú

**Liên kết với:**
- booking (1-N): Khách hàng đặt nhiều tour
- tour_customer (1-N): Khách hàng tham gia các tour
- attendance (1-N): Điểm danh khách hàng

---

## 4. **driver** - Tài xế
**Lưu thông tin:**
- `driver_id` (PK)
- `user_id` (FK) → users
- `fullname` - Họ tên
- `phone` - Điện thoại
- `license_plate` - Biển số xe
- `vehicle_type` - Loại xe
- `status` - Trạng thái [available, busy]
- `notes` - Ghi chú

**Liên kết với:**
- users (N-1): Mỗi tài xế là một user
- departure_schedule (1-N): Tài xế lái nhiều chuyến

---

## 5. **guide** - Hướng dẫn viên
**Lưu thông tin:**
- `guide_id` (PK)
- `user_id` (FK) → users
- `cccd` - Số CCCD
- `language` - Ngoại ngữ
- `certificate` - Chứng chỉ
- `experience` - Kinh nghiệm
- `specialization` - Chuyên môn

**Liên kết với:**
- users (N-1): Mỗi guide là một user
- departure_schedule (1-N): Hướng dẫn viên hướng dẫn nhiều chuyến

---

## 6. **hotel** - Khách sạn
**Lưu thông tin:**
- `hotel_id` (PK)
- `name` - Tên khách sạn
- `address` - Địa chỉ
- `manager_name` - Tên người quản lý
- `manager_phone` - SĐT quản lý

**Liên kết với:**
- departure_schedule (1-N): Khách sạn phục vụ nhiều chuyến
- tour_hotel (N-N): Khách sạn được dùng trong nhiều tour

---

## 7. **tour** - Tour du lịch
**Lưu thông tin:**
- `tour_id` (PK)
- `category_id` (FK) → category
- `name` - Tên tour
- `description` - Mô tả chi tiết
- `policy` - Chính sách
- `supplier` - Nhà cung cấp
- `image` - Hình ảnh
- `price` - Giá (decimal 15,2)
- `max_people` - Số người tối đa
- `duration` - Thời gian (ngày)
- `start_date` - Ngày bắt đầu
- `end_date` - Ngày kết thúc
- `status` - Trạng thái [active, inactive]
- `created_at`, `updated_at` - Thời gian

**Liên kết với:**
- category (N-1): Thuộc một danh mục
- departure_schedule (1-N): Có nhiều lịch khởi hành
- booking (1-N): Có nhiều đơn đặt
- tour_hotel (N-N): Sử dụng nhiều khách sạn
- feedback (1-N): Nhận nhiều đánh giá

---

## 8. **departure_schedule** - Lịch khởi hành
**Lưu thông tin:**
- `schedule_id` (PK)
- `tour_id` (FK) → tour
- `guide_id` (FK) → guide
- `driver_id` (FK) → driver
- `hotel_id` (FK) → hotel
- `start_date` - Ngày khởi hành
- `end_date` - Ngày kết thúc
- `meeting_point` - Điểm tập trung
- `notes` - Ghi chú

**Liên kết với:**
- tour (N-1): Một tour có nhiều lịch khởi hành
- guide (N-1): Một hướng dẫn viên hướng dẫn nhiều chuyến
- driver (N-1): Một tài xế lái nhiều chuyến
- hotel (N-1): Dùng khách sạn cho chuyến này
- booking (1-N): Có nhiều đơn đặt cho chuyến này
- tour_customer (1-N): Khách hàng tham gia chuyến này
- attendance (1-N): Điểm danh cho chuyến này

---

## 9. **booking** - Đơn đặt tour
**Lưu thông tin:**
- `booking_id` (PK)
- `user_id` (FK) → users *(nullable - tài khoản đặt tour)*
- `customer_id` (FK) → customer *(khách hàng thực tế đi tour)*
- `tour_id` (FK) → tour
- `schedule_id` (FK) → departure_schedule
- `booking_date` - Ngày đặt
- `num_people` - Số người đăng ký
- `total_price` - Tổng giá (decimal 10,2)
- `status` - Trạng thái [upcoming, ongoing, completed, cancelled]
- `payment_status` - Trạng thái thanh toán [unpaid, deposit, paid]
- `note` - Ghi chú

**Liên kết với:**
- users (N-1): Tài khoản người đặt tour (có thể null nếu khách vãng lai)
- customer (N-1): Khách hàng thực tế đi tour
- tour (N-1): Tour được đặt nhiều lần
- departure_schedule (N-1): Chuyến được đặt nhiều lần
- payment (1-N): Một booking có thể có nhiều thanh toán
- feedback (1-N): Khách để lại feedback cho booking
- tour_customer (1-1): Liên kết khách với tour này

---

## 10. **payment** - Thanh toán
**Lưu thông tin:**
- `payment_id` (PK)
- `booking_id` (FK) → booking
- `amount` - Số tiền (decimal 15,2)
- `payment_method` - Phương thức thanh toán [cash, bank_transfer, credit_card, e_wallet]
- `status` - Trạng thái [pending, completed, failed, refunded]
- `payment_date` - Ngày thanh toán
- `note` - Ghi chú

**Liên kết với:**
- booking (N-1): Một booking có một hoặc nhiều lần thanh toán

---

## 11. **tour_customer** - Khách hàng tham gia tour
**Lưu thông tin:**
- `id` (PK)
- `schedule_id` (FK) → departure_schedule
- `customer_id` (FK) → customer
- `room_number` - Số phòng
- `checkin_status` - Trạng thái check-in [not_checked_in, checked_in]
- `attendance_status` - Trạng thái tham dự [present, absent, unknown]
- `note` - Ghi chú

**Liên kết với:**
- departure_schedule (N-1): Một chuyến có nhiều khách
- customer (N-1): Khách hàng tham gia nhiều chuyến
- attendance (1-N): Có điểm danh cho khách hàng này

---

## 12. **attendance** - Điểm danh
**Lưu thông tin:**
- `attendance_id` (PK)
- `schedule_id` (FK) → departure_schedule
- `tour_customer_id` (FK) → tour_customer
- `customer_id` (FK) → customer
- `guide_id` (FK) → guide
- `status` - Trạng thái [present, absent, unknown]
- `note` - Ghi chú
- `marked_at` - Thời gian điểm danh

**Liên kết với:**
- departure_schedule (N-1): Một chuyến có nhiều điểm danh
- tour_customer (N-1): Mỗi tour_customer có nhiều lần điểm danh
- customer (N-1): Khách hàng được điểm danh nhiều lần
- guide (N-1): Hướng dẫn viên điểm danh nhiều lần

---

## 13. **feedback** - Đánh giá/Phản hồi
**Lưu thông tin:**
- `id` (PK)
- `booking_id` (FK) → booking
- `type` - Loại [danh_gia, su_co]
- `rating` - Xếp hạng 1-5 (cho đánh giá)
- `content` - Nội dung phản hồi
- `created_at` - Thời gian tạo

**Liên kết với:**
- booking (N-1): Mỗi booking có thể có một hoặc nhiều feedback

---

## 14. **tour_hotel** - Tour sử dụng Khách sạn
**Lưu thông tin:**
- `id` (PK)
- `tour_id` (FK) → tour
- `hotel_id` (FK) → hotel
- `location` - Địa điểm

**Liên kết với:**
- tour (N-1): Một tour sử dụng nhiều khách sạn
- hotel (N-1): Một khách sạn phục vụ nhiều tour

---

## Sơ đồ quan hệ chính

```
users
  ├── guide (1-1 hoặc N-1)
  ├── driver (1-1 hoặc N-1)
  ├── booking (1-N) - user đặt tour
  └── feedback (1-N) qua booking

category
  └── tour (1-N)

tour (1-N)
  ├── departure_schedule (1-N)
  ├── booking (1-N)
  ├── tour_hotel (N-N)
  └── feedback (1-N)

departure_schedule
  ├── guide (N-1)
  ├── driver (N-1)
  ├── hotel (N-1)
  ├── booking (1-N)
  ├── tour_customer (1-N)
  └── attendance (1-N)

customer
  ├── booking (1-N) - khách hàng được đặt tour
  ├── tour_customer (1-N)
  └── attendance (1-N)

booking (1-N)
  ├── users (N-1) - người đặt
  ├── customer (N-1) - người đi tour
  ├── payment (1-N)
  ├── feedback (1-N)
  └── tour_customer (1-1)

hotel (1-N)
  ├── departure_schedule (1-N)
  └── tour_hotel (N-N)
```

---

## Ghi chú quan trọng

1. **User vs Customer**: 
   - **User**: Tài khoản đăng nhập hệ thống (admin, tour_guide, user có tài khoản)
   - **Customer**: Thông tin chi tiết của người tham gia tour (có thể không có tài khoản)
   - **Booking**: Có thể user đăng ký tài khoản đặt tour cho customer (ví dụ: đặt cho gia đình)

2. **Guide & Driver**: Cả hai kết nối với users table thông qua user_id

3. **Booking & Payment**: Tách riêng để dễ quản lý (một booking có thể có nhiều thanh toán - cọc, thanh toán dần)

4. **Tour_customer & Attendance**: tour_customer là danh sách khách trong chuyến, attendance là điểm danh chi tiết

5. **Departure_schedule**: Là khoá để liên kết tất cả thông tin của một chuyến cụ thể (tour, hướng dẫn viên, tài xế, khách sạn)
