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

    .quick-link-card {
        border: 1px solid #e9eef5;
        border-radius: 16px;
        padding: 24px;
        background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 100%);
    }

    .quick-link-card p {
        color: var(--text-gray);
        margin-bottom: 16px;
    }

    .quick-link-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        border-radius: 12px;
        background: var(--primary-blue);
        color: var(--white);
        text-decoration: none;
        font-weight: 700;
    }

    .quick-link-btn:hover {
        background: var(--secondary-blue);
    }

    /* Responsive */
    @media (max-width: 900px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .sidebar {
            display: none;
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
                        <a href="{{ route('user.bookings') }}" class="nav-link">
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
                        Trạng thái booking
                    </h2>
                    <div class="quick-link-card">
                        <p>Theo dõi toàn bộ booking của bạn tại một trang riêng, gồm các nhóm chờ thanh toán, chờ admin xác nhận, đã xác nhận và đã hủy.</p>
                        <a href="{{ route('user.bookings') }}" class="quick-link-btn">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            Mở trang trạng thái booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endsection