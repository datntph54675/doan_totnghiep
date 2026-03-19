<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin</title>
    <link href="/css/app.css" rel="stylesheet">
    <style>
        :root {
            --accent: #0f62fe;
            --muted: #6b7280;
            --card: #fff
        }

        * {
            box-sizing: border-box
        }

        body {
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial;
            margin: 0;
            background: #f6fbff;
            color: #0b2540;
            min-height: 100vh;
            display: flex
        }

        /* Sidebar */
        .sidebar {
            width: 300px;
            background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #047857 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 28px;
            gap: 18px
        }

        .sidebar .brand {
            display: flex;
            gap: 12px;
            align-items: center
        }

        .sidebar .logo {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 12px
        }

        .sidebar nav a {
            color: rgba(255, 255, 255, .95);
            padding: 10px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600
        }
        /* Main */
        .main {
            flex: 1;
            padding: 28px
        }

        h2 {
            margin: 0 0 12px
        }

        .muted-sm {
            color: var(--muted);
            font-size: 13px
        }

        .btn-logout {
            background: var(--accent);
            color: #fff;
            padding: 8px 12px;
            border-radius: 8px;
            border: 0;
            cursor: pointer
        }

        .card {
            background: var(--card);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 8px 30px rgba(2, 6, 23, 0.06)
        }

        .table-card {
            background: var(--card);
            border-radius: 12px;
            padding: 14px;
            box-shadow: 0 8px 30px rgba(2, 6, 23, 0.06)
        }

        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px
        }

        table.admin-table thead th {
            background: linear-gradient(90deg, #f3f7ff, #eef6ff);
            color: #0b2540;
            font-weight: 700;
            padding: 10px;
            text-align: left
        }

        table.admin-table tbody td {
            padding: 10px;
            vertical-align: middle;
            border-top: 1px solid #f1f5f9
        }

        @media(max-width:900px) {
            .sidebar {
                display: none
            }

            .main {
                padding: 16px
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="brand">
            <div class="logo">DV</div>
            <div>
                <div style="font-weight:800">TourViet Admin</div>
                <div style="font-size:13px;color:rgba(255,255,255,.85)">Quản trị hệ thống</div>
            </div>
        </div>

        <nav>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Bảng điều khiển</a>
            <a href="{{ route('admin.tours.index') }}" class="{{ request()->routeIs('admin.tours.*') ? 'active' : '' }}">Quản lý Tour</a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Danh mục</a>
            <a href="#" class="">Đơn đặt tour</a>
            <a href="#" class="">Khách hàng</a>
            <a href="#" class="">Hướng dẫn viên</a>
        </nav>

        <div style="margin-top:auto">
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn-logout" style="width:100%">Đăng xuất</button></form>
        </div>
    </div>

    <main class="main">
        @yield('content')
    </main>
</body>

</html>