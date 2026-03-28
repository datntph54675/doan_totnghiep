@extends('layouts.user')

@section('title', 'Hồ sơ cá nhân - VietTour')

@section('styles')
<style>
    :root {
        --primary-blue: #0066cc;
        --secondary-blue: #004d99;
        --accent-blue: #e6f2ff;
        --text-dark: #2d3436;
        --text-gray: #636e72;
        --bg-light: #f8fafc;
        --white: #ffffff;
        --danger: #d63031;
        --success: #00b894;
    }

    .profile-page {
        background: var(--bg-light);
        min-height: calc(100vh - 80px);
        padding: 50px 0;
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
    }

    /* Sidebar Styles */
    .sidebar {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 30px 20px;
        height: fit-content;
    }

    .user-brief {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 1px solid #f1f2f6;
    }

    .avatar-circle {
        width: 80px;
        height: 80px;
        background: var(--primary-blue);
        color: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        margin: 0 auto 15px;
        box-shadow: 0 8px 15px rgba(0, 102, 204, 0.2);
    }

    .user-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 5px;
    }

    .user-role {
        font-size: 14px;
        color: var(--text-gray);
        background: #f1f2f6;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
    }

    .nav-menu {
        list-style: none;
        padding: 0;
    }

    .nav-item {
        margin-bottom: 10px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
        border-radius: 12px;
        color: var(--text-gray);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .nav-link.active {
        background: var(--accent-blue);
        color: var(--primary-blue);
    }

    .nav-link:hover:not(.active) {
        background: #f8fafc;
        color: var(--text-dark);
        transform: translateX(5px);
    }

    /* Content Area Styles */
    .content-card {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 40px;
        margin-bottom: 30px;
    }

    .card-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-title i {
        color: var(--primary-blue);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 10px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #edeff2;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s;
        color: var(--text-dark);
    }

    .form-control:focus {
        border-color: var(--primary-blue);
        outline: none;
        box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.05);
    }

    .form-control:read-only {
        background: #f8fafc;
        cursor: not-allowed;
    }

    .btn-update {
        background: var(--primary-blue);
        color: var(--white);
        border: none;
        padding: 14px 28px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-update:hover {
        background: var(--secondary-blue);
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 102, 204, 0.2);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 14px;
        margin-bottom: 25px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        background: #e6fffa;
        color: #2c7a7b;
        border: 1px solid #b2f5ea;
    }

    .alert-danger {
        background: #fff5f5;
        color: #c53030;
        border: 1px solid #feb2b2;
    }

    .booking-list {
        display: grid;
        gap: 16px;
    }

    .booking-item {
        border: 1px solid #e9eef5;
        border-radius: 14px;
        padding: 16px;
        background: #fcfdff;
    }

    .booking-item-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        flex-wrap: wrap;
    }

    .booking-title {
        margin: 0;
        font-size: 17px;
        color: var(--text-dark);
        font-weight: 700;
    }

    .status-confirmed {
        background: #dcfce7;
        color: #166534;
        border-radius: 999px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
        border-radius: 999px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .booking-section-title {
        margin: 14px 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--text-dark);
    }

    .booking-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px 16px;
        color: var(--text-gray);
        font-size: 14px;
        margin: 8px 0 12px;
    }

    .booking-price {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary-blue);
    }

    .booking-link {
        text-decoration: none;
        font-weight: 600;
        color: var(--primary-blue);
    }

    .empty-state {
        border: 1px dashed #d8deea;
        background: #fbfcfe;
        padding: 20px;
        border-radius: 12px;
        color: var(--text-gray);
        font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .sidebar {
            display: none;
        }

        .booking-meta {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-page">
    <div class="container">
        <div class="dashboard-grid">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="user-brief">
                    <div class="avatar-circle">
                        {{ strtoupper(substr($user->fullname, 0, 1)) }}
                    </div>
                    <h3 class="user-name">{{ $user->fullname }}</h3>
                    <span class="user-role">Khách hàng</span>
                </div>

                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="#info" class="nav-link active">
                            <i class="fa-solid fa-user-circle"></i>
                            Thông tin cá nhân
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#bookings" class="nav-link">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            Lịch sử đặt tour
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link text-danger">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Content Area -->
            <div class="main-content">
                @if (session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
                @endif

                <!-- Profile Info Card -->
                <div class="content-card" id="info">
                    <h2 class="card-title">
                        <i class="fa-solid fa-id-card"></i>
                        Thông tin cá nhân
                    </h2>

                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Tên tài khoản</label>
                                <input type="text" class="form-control" value="{{ $user->username }}" readonly>
                                <small style="color: #b2bec3;">Không thể thay đổi username</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror" value="{{ old('fullname', $user->fullname) }}" required>
                                @error('fullname') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" required>
                                @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div style="text-align: right; margin-top: 10px;">
                            <button type="submit" class="btn-update">
                                <i class="fa-solid fa-save"></i>
                                Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Change Card -->
                <div class="content-card">
                    <h2 class="card-title">
                        <i class="fa-solid fa-shield-halved"></i>
                        Đổi mật khẩu
                    </h2>

                    <form action="{{ route('user.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required placeholder="Nhập mật khẩu đang dùng">
                            @error('current_password') <span style="color: var(--danger); font-size: 13px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Tối thiểu 6 ký tự">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" name="password_confirmation" class="form-control" required placeholder="Nhập lại mật khẩu mới">
                            </div>
                        </div>
                        @error('password') <div style="color: var(--danger); font-size: 13px; margin-bottom: 15px;">{{ $message }}</div> @enderror

                        <div style="text-align: right; margin-top: 10px;">
                            <button type="submit" class="btn-update" style="background: #2d3436;">
                                <i class="fa-solid fa-key"></i>
                                Cập nhật mật khẩu
                            </button>
                        </div>
                    </form>
                </div>

                <div class="content-card" id="bookings">
                    <h2 class="card-title">
                        <i class="fa-solid fa-circle-check"></i>
                        Trạng thái booking của bạn
                    </h2>

                    <h3 class="booking-section-title">
                        Chờ admin xác nhận ({{ ($pendingBookings ?? collect())->count() }})
                    </h3>
                    @if (($pendingBookings ?? collect())->isEmpty())
                    <div class="empty-state">
                        Hiện không có booking nào đang chờ admin xác nhận.
                    </div>
                    @else
                    <div class="booking-list">
                        @foreach ($pendingBookings as $booking)
                        <article class="booking-item">
                            <div class="booking-item-head">
                                <h3 class="booking-title">{{ $booking->tour->name ?? 'Tour không xác định' }}</h3>
                                <span class="status-pending">Chờ xác nhận</span>
                            </div>

                            <div class="booking-meta">
                                <div>Mã booking: #{{ $booking->booking_id }}</div>
                                <div>Ngày đặt: {{ optional($booking->booking_date)->format('d/m/Y H:i') }}</div>
                                <div>Khởi hành: {{ optional(optional($booking->schedule)->start_date)->format('d/m/Y') ?? '-' }}</div>
                                <div>Số người: {{ $booking->num_people }} người</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="booking-price">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</div>
                                @if ($booking->tour_id)
                                <a class="booking-link" href="{{ route('tours.show', $booking->tour_id) }}">Xem tour</a>
                                @endif
                            </div>
                        </article>
                        @endforeach
                    </div>
                    @endif

                    <h3 class="booking-section-title">
                        Đã admin xác nhận ({{ ($confirmedBookings ?? collect())->count() }})
                    </h3>
                    @if (($confirmedBookings ?? collect())->isEmpty())
                    <div class="empty-state">
                        Hiện chưa có tour nào được admin xác nhận. Sau khi admin duyệt booking đã thanh toán, tour sẽ hiển thị tại đây.
                    </div>
                    @else
                    <div class="booking-list">
                        @foreach ($confirmedBookings as $booking)
                        <article class="booking-item">
                            <div class="booking-item-head">
                                <h3 class="booking-title">{{ $booking->tour->name ?? 'Tour không xác định' }}</h3>
                                <span class="status-confirmed">Đã xác nhận</span>
                            </div>

                            <div class="booking-meta">
                                <div>Mã booking: #{{ $booking->booking_id }}</div>
                                <div>Ngày đặt: {{ optional($booking->booking_date)->format('d/m/Y H:i') }}</div>
                                <div>Khởi hành: {{ optional(optional($booking->schedule)->start_date)->format('d/m/Y') ?? '-' }}</div>
                                <div>Số người: {{ $booking->num_people }} người</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="booking-price">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</div>
                                @if ($booking->tour_id)
                                <a class="booking-link" href="{{ route('tours.show', $booking->tour_id) }}">Xem tour</a>
                                @endif
                            </div>
                        </article>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endsection