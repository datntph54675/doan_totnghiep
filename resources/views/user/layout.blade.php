<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tour Du Lịch')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; color: #1e293b; }

        /* NAV */
        .navbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .navbar-brand {
            font-size: 22px; font-weight: 800;
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-decoration: none;
        }
        .navbar-links { display: flex; gap: 28px; }
        .navbar-links a { text-decoration: none; color: #64748b; font-size: 15px; font-weight: 500; transition: color .2s; }
        .navbar-links a:hover { color: #0ea5e9; }

        /* MAIN */
        .container { max-width: 1100px; margin: 0 auto; padding: 0 20px; }

        /* FOOTER */
        footer { background: #1e293b; color: #94a3b8; text-align: center; padding: 28px 20px; margin-top: 60px; font-size: 14px; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar">
    <a href="{{ route('user.tours') }}" class="navbar-brand">✈️ TourViet</a>
    <div class="navbar-links">
        <a href="{{ route('user.tours') }}">Tất cả tour</a>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer>
    <p>© 2026 TourViet. Hệ thống quản lý tour du lịch.</p>
</footer>
@stack('scripts')
</body>
</html>
