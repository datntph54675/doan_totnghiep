@extends('layouts.user')

@section('title', 'Đăng nhập - VietTour')

@section('styles')
<style>
    .auth-container {
        min-height: calc(100vh - 300px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background: var(--bg-light);
    }

    .auth-card {
        width: 100%;
        max-width: 420px;
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
            <h1>Đăng nhập</h1>
            <p>Mừng bạn quay trở lại với VietTour</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Địa chỉ Email</label>
                <div class="row-input">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu</label>
                <div class="row-input">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="nhập mật khẩu" required>
                </div>
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--text-mid); cursor: pointer;">
                    <input type="checkbox" name="remember" style="width: 16px; height: 16px;"> Nhớ đăng nhập
                </label>
                <a href="{{ route('password.request') }}" style="font-size: 0.85rem; color: var(--primary); font-weight: 600;">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn-auth">Đăng nhập ngay</button>
        </form>

        <div class="auth-footer">
            Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
        </div>
    </div>
</div>
@endsection