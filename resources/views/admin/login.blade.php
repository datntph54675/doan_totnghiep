<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - TourViet</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
        }

        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #0b4a6f 0%, #065f46 40%, #047857 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, .04);
            border-radius: 50%;
            top: -100px;
            right: -100px
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, .04);
            border-radius: 50%;
            bottom: -80px;
            left: -80px
        }

        .left-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: #fff
        }

        .left-icon {
            font-size: 64px;
            margin-bottom: 24px;
            display: block
        }

        .left-title {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 12px;
            letter-spacing: -.5px
        }

        .left-sub {
            font-size: 16px;
            color: rgba(255, 255, 255, .8);
            line-height: 1.6;
            max-width: 360px
        }

        .features {
            margin-top: 32px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            text-align: left
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: rgba(255, 255, 255, .9)
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, .12);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0
        }

        .right-panel {
            width: 480px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px
        }

        .login-box {
            width: 100%
        }

        .login-header {
            margin-bottom: 32px
        }

        .login-logo {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 20px
        }

        .login-title {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -.5px
        }

        .login-sub {
            font-size: 14px;
            color: #64748b;
            margin-top: 6px
        }

        .form-group {
            margin-bottom: 18px
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            color: #0f172a;
            background: #f8fafc
        }

        .form-control:focus {
            outline: none;
            border-color: #10b981;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .08)
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px
        }

        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .hint {
            margin-top: 20px;
            padding: 14px 16px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            font-size: 13px;
            color: #166534
        }

        .back-home {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #64748b;
            text-decoration: none
        }

        .back-home:hover {
            color: #10b981
        }

        @media(max-width:768px) {
            .left-panel {
                display: none
            }

            .right-panel {
                width: 100%;
                padding: 32px 24px
            }
        }
    </style>
</head>

<body>

    <div class="left-panel">
        <div class="left-content">
            <span class="left-icon">🧭</span>
            <div class="left-title">TourViet Admin</div>
            <div class="left-sub">Cổng quản trị hệ thống — quản lý tour, khách hàng và đơn đặt.</div>

            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">📋</div><span>Quản lý danh sách tour</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">👥</div><span>Quản lý khách hàng và đơn</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📅</div><span>Quản lý lịch khởi hành</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">💬</div><span>Quản lý phản hồi</span>
                </div>
            </div>
        </div>
    </div>

    <div class="right-panel">
        <div class="login-box">
            <div class="login-header">
                <div class="login-logo">DV</div>
                <div class="login-title">Đăng nhập Admin</div>
                <div class="login-sub">Đăng nhập để tiếp tục quản trị hệ thống</div>
            </div>

            @if($errors->any())
            <div class="error-box">⚠️ {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="username">Tên đăng nhập</label>
                    <input class="form-control" id="username" name="username" type="text" autocomplete="username" value="{{ old('username') }}" placeholder="Nhập tên đăng nhập" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Mật khẩu</label>
                    <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" placeholder="Nhập mật khẩu" required>
                </div>

                <button type="submit" class="btn-login">Đăng nhập →</button>
            </form>

            <div class="hint">🔑 Tài khoản thử nghiệm: <strong>admin</strong> / <strong>123456</strong></div>
            <a href="/" class="back-home">← Về trang chủ</a>
        </div>
    </div>

</body>

</html>