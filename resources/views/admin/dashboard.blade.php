<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển Admin</title>
    <link href="/css/app.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f8fb;
            --card: #fff;
            --accent: #2563eb;
            --muted: #6b7280
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial;
            background: var(--bg);
            color: #111
        }

        .container {
            max-width: 1100px;
            margin: 28px auto;
            padding: 20px
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px
        }

        .title {
            font-size: 20px;
            font-weight: 700
        }

        .user-box {
            display: flex;
            align-items: center;
            gap: 12px
        }

        .avatar {
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

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 18px
        }

        .card {
            background: var(--card);
            border-radius: 10px;
            padding: 18px;
            box-shadow: 0 8px 24px rgba(2, 6, 23, 0.06)
        }

        .stat {
            font-size: 28px;
            font-weight: 700
        }

        .muted {
            color: var(--muted);
            font-size: 13px
        }

        .actions form {
            display: inline
        }

        .btn-logout {
            background: #fff;
            border: 1px solid #e5e7eb;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="topbar">
@extends('layouts.app')

            <div>
                <div class="title">Bảng điều khiển Admin</div>
                <div class="muted">Chào mừng, {{ auth()->user()->fullname ?? auth()->user()->username }}.</div>
            </div>

            <div class="user-box">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->username ?? 'AD',0,2)) }}</div>
                <div>
                    <div style="font-weight:600">{{ auth()->user()->fullname ?? auth()->user()->username }}</div>
                    <div class="muted">Role: {{ auth()->user()->role }}</div>
                </div>
                <div class="actions">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="btn-logout" type="submit">Đăng xuất</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-grid">
            <div class="card">
                <div class="muted">Tổng số tour</div>
                <div class="stat">{{ \DB::table('tours')->count() }}</div>
            </div>

            <div class="card">
                <div class="muted">Đơn đặt tour</div>
                <div class="stat">{{ \DB::table('booking')->count() }}</div>
            </div>

            <div class="card">
                <div class="muted">Khách hàng</div>
                <div class="stat">{{ \DB::table('customer')->count() }}</div>
            </div>

            <div class="card">
                <div class="muted">Hướng dẫn viên</div>
                <div class="stat">{{ \DB::table('guide')->count() }}</div>
            </div>
        </div>
    </div>
</body>

</html>