@extends('layouts.user')

@section('title', 'Đăng ký tài khoản - GoTour')

@section('styles')
<style>
    .auth-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background: var(--bg-light);
    }

    .auth-card {
        width: 100%;
        max-width: 500px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        padding: 40px;
        border: 1px solid var(--border);
    }

    .auth-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .auth-header h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .auth-header p {
        color: var(--text-light);
        font-size: 0.9rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 480px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-mid);
        margin-bottom: 8px;
    }

    .row-input {
        position: relative;
    }

    .row-input i {
        position: absolute;
        left: 14px;
        top: 14px;
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-family: inherit;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.1);
    }

    .btn-auth {
        width: 100%;
        padding: 14px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        margin-top: 10px;
        transition: all 0.2s;
    }

    .btn-auth:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 102, 204, 0.2);
    }

    .auth-footer {
        text-align: center;
        margin-top: 24px;
        font-size: 0.9rem;
        color: var(--text-mid);
    }

    .auth-footer a {
        color: var(--primary);
        font-weight: 600;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    .error-msg {
        color: #dc2626;
        font-size: 0.75rem;
        margin-top: 6px;
        display: block;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Tạo tài khoản mới</h1>
            <p>Khám phá vạn dặm hành trình cùng chúng tôi</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Họ và tên</label>
                <div class="row-input">
                    <i class="fas fa-user"></i>
                    <input type="text" name="fullname" class="form-control" placeholder="Nguyễn Văn A" value="{{ old('fullname') }}" required>
                </div>
                @error('fullname') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tên đăng nhập</label>
                    <div class="row-input">
                        <i class="fas fa-id-card"></i>
                        <input type="text" name="username" class="form-control" placeholder="user123" value="{{ old('username') }}" required>
                    </div>
                    @error('username') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <div class="row-input">
                        <i class="fas fa-phone"></i>
                        <input type="text" name="phone" class="form-control" placeholder="09xxxxxxxx" value="{{ old('phone') }}" required>
                    </div>
                    @error('phone') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Địa chỉ Email</label>
                <div class="row-input">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com" value="{{ old('email') }}" required>
                </div>
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Mật khẩu</label>
                    <div class="row-input">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="nhập mật khẩu" required>
                    </div>
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Xác nhận mật khẩu</label>
                    <div class="row-input">
                        <i class="fas fa-check-circle"></i>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="nhập lại mật khẩu" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-auth">Đăng ký tài khoản</button>
        </form>

        <div class="auth-footer">
            Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
        </div>
    </div>
</div>
@endsection
