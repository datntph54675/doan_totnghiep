@extends('layouts.user')

@section('title', 'Đặt lại mật khẩu - VietTour')

@section('styles')
<style>
    :root {
        --primary-blue: #0066cc;
        --secondary-blue: #004d99;
        --accent-blue: #e6f2ff;
        --text-dark: #2d3436;
        --bg-light: #f8fafc;
        --white: #ffffff;
    }

    .auth-container {
        min-height: calc(100vh - 400px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        background: var(--bg-light);
        position: relative;
        overflow: hidden;
    }

    .auth-bg-decor {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        z-index: 1; pointer-events: none;
    }
    .bubble {
        position: absolute; background: rgba(0, 102, 204, 0.05);
        border-radius: 50%;
        animation: float 15s infinite ease-in-out;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-30px) scale(1.1); }
    }

    .auth-card {
        width: 100%;
        max-width: 500px;
        background: var(--white);
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
        padding: 50px;
        position: relative;
        z-index: 2;
        border: 1px solid rgba(0, 102, 204, 0.05);
    }

    .auth-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .auth-icon {
        width: 70px;
        height: 70px;
        background: var(--accent-blue);
        color: var(--primary-blue);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        margin: 0 auto 20px;
    }

    .auth-title {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 12px;
    }

    .auth-subtitle {
        color: #636e72;
        font-size: 15px;
        line-height: 1.6;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 10px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-wrapper i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #b2bec3;
        transition: color 0.3s;
    }

    .form-control {
        width: 100%;
        padding: 15px 15px 15px 50px;
        border: 2px solid #edeff2;
        border-radius: 14px;
        font-size: 15px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: var(--text-dark);
    }

    .form-control:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.1);
        outline: none;
    }

    .btn-submit {
        width: 100%;
        background: var(--primary-blue);
        color: var(--white);
        border: none;
        padding: 16px;
        border-radius: 14px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-submit:hover {
        background: var(--secondary-blue);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 102, 204, 0.2);
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

    .alert-error {
        background: #fff5f5;
        color: #c53030;
        border: 1px solid #feb2b2;
    }

    .input-help {
        display: block;
        font-size: 12px;
        color: #b2bec3;
        margin-top: 6px;
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-bg-decor">
        <div class="bubble" style="width: 100px; height: 100px; top: 10%; left: 5%;"></div>
        <div class="bubble" style="width: 80px; height: 80px; top: 20%; right: 10%; animation-delay: 5s;"></div>
    </div>

    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h1 class="auth-title">Đổi mật khẩu mới</h1>
            <p class="auth-subtitle">Gần xong rồi! Hãy nhập mật khẩu cực kỳ bảo mật của bạn dưới đây.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>Có lỗi xảy ra, vui lòng kiểm tra lại.</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email" class="form-label">Địa chỉ Email</label>
                <div class="input-wrapper">
                    <input id="email" type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>
                    <i class="fa-solid fa-envelope"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu mới</label>
                <div class="input-wrapper">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Nhập ít nhất 6 ký tự">
                    <i class="fa-solid fa-lock"></i>
                </div>
                @error('password')
                    <span class="input-help text-danger" style="color: #c53030;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm" class="form-label">Xác nhận mật khẩu mới</label>
                <div class="input-wrapper">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Nhập lại mật khẩu giống bên trên">
                    <i class="fa-solid fa-lock"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Cập nhật mật khẩu
                <i class="fa-solid fa-check"></i>
            </button>
        </form>
    </div>
</div>
@endsection
