<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Guide</title>
    <link href="/css/app.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f3f6fb;
            --card: #ffffff;
            --accent: #2563eb;
            --muted: #6b7280
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: var(--bg);
            color: #111
        }

        .wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: var(--card);
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 8px 30px rgba(2, 6, 23, 0.08)
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px
        }

        .logo {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--accent), #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700
        }

        h2 {
            margin: 0;
            font-size: 20px
        }

        p.lead {
            margin: 6px 0 18px;
            color: var(--muted);
            font-size: 14px
        }

        .field {
            margin-bottom: 14px
        }

        label {
            display: block;
            font-size: 13px;
            color: #111;
            margin-bottom: 6px;
            font-weight: 600
        }

        input[type=text],
        input[type=password] {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #e6e9ef;
            border-radius: 8px;
            font-size: 14px;
            background: #fbfdff
        }

        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 8px
        }

        .btn {
            background: var(--accent);
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            border: 0;
            cursor: pointer;
            font-weight: 600
        }

        .btn.secondary {
            background: #eef2ff;
            color: var(--accent);
            border: 1px solid rgba(37, 99, 235, 0.08)
        }

        .error {
            background: #fff5f5;
            border: 1px solid #f8d7da;
            color: #b91c1c;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 12px
        }

        .help {
            font-size: 13px;
            color: var(--muted);
            margin-top: 12px
        }

        @media(max-width:480px) {
            .card {
                padding: 20px;
                border-radius: 10px
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <div class="brand">
                <div class="logo">DV</div>
                <div>
                    <h2>Login Guide</h2>
                    <p class="lead">Đăng nhập để quản trị hệ thống</p>
                </div>
            </div>

            @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('guide.login.post') }}">
                @csrf
                <div class="field">
                    <label for="username">Tên đăng nhập</label>
                    <input id="username" name="username" type="text" autocomplete="username" value="{{ old('username') }}" required>
                </div>

                <div class="field">
                    <label for="password">Mật khẩu</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                </div>

                <div class="actions">
                    <button class="btn" type="submit">Đăng nhập</button>
                    <a class="btn secondary" href="/">Về trang chính</a>
                </div>
            </form>

            <p class="help">Tài khoản thử: <strong>guide</strong> / <strong>123456</strong></p>
        </div>
    </div>
</body>

</html>