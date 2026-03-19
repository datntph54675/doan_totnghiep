<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
            max-width: 720px;
            background: var(--card);
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 8px 30px rgba(2, 6, 23, 0.08)
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px
        }

        .muted {
            color: var(--muted)
        }

        .btn {
            background: var(--accent);
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            border: 0;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center
        }

        .btn.secondary {
            background: #eef2ff;
            color: var(--accent);
            border: 1px solid rgba(37, 99, 235, 0.08)
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <div class="row">
                <div>
                    <h2 style="margin:0">Dashboard</h2>
                    <div class="muted" style="margin-top:6px">
                        Xin chào, <strong>{{ auth()->user()->fullname ?? auth()->user()->username }}</strong>
                        (role: <strong>{{ auth()->user()->role }}</strong>)
                    </div>
                </div>

                <div class="row">
                    <a class="btn secondary" href="/">Trang chủ</a>
                    <a class="btn secondary" href="{{ route('profile.show') }}">Cập nhật thông tin</a>
                    <a class="btn secondary" href="{{ route('password.change') }}">Đổi mật khẩu</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0">
                        @csrf
                        <button class="btn" type="submit">Đăng xuất</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

