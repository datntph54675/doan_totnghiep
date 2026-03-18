## Plan: Website du lịch (Admin / Guide / User)

TL;DR: Xây một ứng dụng Laravel dựa trên schema hiện có (migrations đã có tour, hotel, booking, guide, v.v.), dùng cột `role` trong bảng `users` để phân quyền (admin, tour_guide, user). Triển khai authentication, models đầy đủ, controllers, routes, views (server-rendered hoặc API + SPA), seeders, và các policy/middleware để bảo vệ chức năng theo role.

**Steps**
1. Chuẩn hoá model `User` và cấu hình Eloquent (primaryKey, table, `$fillable`) — *blocking: fixes migration/model mismatch*.
2. Thiết kế & tạo các Models cho domain: `Tour`, `Hotel`, `Booking`, `Guide`, `Customer`, `DepartureSchedule`, `Itinerary`, `Payment`, `Category`, `TourHotel`. (*parallelizable*)
3. Tạo migration bổ sung nếu cần (indexes, FK fixes) và seeders cho dữ liệu thử nghiệm (tours, hotels, guides, customers).
4. Thêm authentication scaffolding:
   - Option A: Sử dụng Laravel Breeze/Jetstream (fast).
   - Option B: Tự implement auth controllers (login/register/logout, password reset) dựa trên bảng `password_reset_tokens` hiện có.
5. Tạo controllers và RESTful routes:
   - `TourController`, `BookingController`, `GuideController`, `HotelController`, `CustomerController`, `Admin\DashboardController`, `Auth\AuthController`.
   - Định nghĩa route groups có middleware `auth` và middleware custom `role:admin`, `role:guide`.
6. Implement role middleware và policies:
   - Middleware `EnsureRole` để kiểm tra `auth()->user()->role`.
   - Policies cho `Booking`, `Tour` (authorize actions: create/update/delete/approve).
7. UI / Views hoặc API:
   - Nếu server-rendered: tạo views trong `resources/views` cho frontend user, guide, admin.
   - Nếu SPA/API: tạo API routes, resources (JSON), và cung cấp mẫu frontend (Vue/React) hoặc tích hợp với `resources/js` hiện tại.
8. Business features implementation (per role):
   - Admin: quản lý tours/hotels/guides/bookings/users, dashboard reports.
   - Guide: xem assigned tours, cập nhật itinerary, attendance, feedback.
   - User: duyệt tour, đặt tour (booking), thanh toán (basic), profile.
9. Testing & Verification: viết feature tests cho authentication, role access, booking flow, and migrations.
10. Deployment checklist: ENV, queue, storage link, cron (if needed), seeders, docs.

**Relevant files**
- [app/Models/User.php](app/Models/User.php) — chỉnh sửa để phù hợp migration hiện tại
- [database/migrations/0001_01_01_000000_create_users_table.php](database/migrations/0001_01_01_000000_create_users_table.php)
- [database/migrations/2026_03_17_160758_create_tour_table.php](database/migrations/2026_03_17_160758_create_tour_table.php)
- [database/migrations/2026_03_17_160754_create_hotel_table.php](database/migrations/2026_03_17_160754_create_hotel_table.php)
- [database/migrations/2026_03_17_160809_create_booking_table.php](database/migrations/2026_03_17_160809_create_booking_table.php)
- [database/migrations/2026_03_17_160759_create_guide_table.php](database/migrations/2026_03_17_160759_create_guide_table.php)
- [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php)
- [routes/web.php](routes/web.php)
- [resources/views/welcome.blade.php](resources/views/welcome.blade.php)

**Verification**
1. Run `php artisan migrate:fresh --seed` and confirm tables & seeders run without errors.
2. Create test users with each role and manually verify protected routes are accessible only to proper roles.
3. Automated tests: add PHPUnit feature tests for login/register, booking creation, and admin-only endpoints.
4. Smoke test UI flows: browse tours, create booking, guide updates, admin CRUD.

**Decisions / Assumptions**
- Use the existing `role` enum column in `users` for RBAC unless you prefer Spatie for fine-grained permissions.
- Tables use singular names (e.g., `tour`) — models must set `$table` or migrations be modified.
- Choose between server-rendered views (faster to build) or API + SPA (better UX). Pick one now.

**Further Considerations**
1. Do you want to use a package (spatie/laravel-permission) or simple enum-based role checks? Recommendation: start with enum-based middleware for MVP, migrate to spatie later if needed.
2. Preferred frontend: server-rendered Blade (quick) or SPA (Vue/React) ?
3. Payment integration: mock/stub for MVP or real gateway (PayPal/Stripe)?

---

Nếu bạn đồng ý với hướng này, mình sẽ: (A) cập nhật `app/Models/User.php` để khớp migration và (B) scaffold models + controllers + routes cơ bản cho `Tour`/`Booking`/`Guide`/`Hotel` với middleware role. Bạn chọn (A) hoặc (B) hoặc yêu cầu ưu tiên khác.