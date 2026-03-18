<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HDV Portal') - TourViet</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            --primary: #059669;
            --primary-light: #10b981;
            --primary-dark: #047857;
            --primary-bg: #ecfdf5;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active: #10b981;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-muted: #64748b;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --shadow: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05);
            --radius: 12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: transform .3s;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--primary-light), #34d399);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }

        .brand-name {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.3px;
        }

        .brand-sub {
            font-size: 11px;
            color: var(--sidebar-text);
            margin-top: 2px;
        }

        .sidebar-user {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--primary-light), #6366f1);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 11px;
            color: var(--primary-light);
            margin-top: 2px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 8px 6px;
            margin-top: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .2s;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
        }

        .nav-item.active {
            background: rgba(16,185,129,.15);
            color: var(--sidebar-active);
        }

        .nav-icon { font-size: 18px; width: 22px; text-align: center; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: #f87171;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            transition: all .2s;
        }

        .logout-btn:hover { background: rgba(239,68,68,.1); }

        /* ── MAIN ── */
        .main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
        }

        .page-sub {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .content {
            padding: 28px;
            flex: 1;
        }

        /* ── CARDS ── */
        .card {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .card-header {
            padding: 20px 24px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-body { padding: 20px 24px 24px; }

        /* ── STAT CARDS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 20px 24px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all .2s;
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-icon.green  { background: #d1fae5; }
        .stat-icon.blue   { background: #dbeafe; }
        .stat-icon.orange { background: #ffedd5; }
        .stat-icon.purple { background: #ede9fe; }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* ── BADGES ── */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info    { background: #dbeafe; color: #1e40af; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-gray    { background: #f1f5f9; color: #475569; }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }
        .btn-primary:hover { background: var(--primary-dark); box-shadow: 0 4px 12px rgba(5,150,105,.3); }

        .btn-outline {
            background: #fff;
            color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-outline:hover { background: var(--bg); }

        .btn-sm { padding: 6px 12px; font-size: 13px; }

        /* ── TOUR ITEMS ── */
        .tour-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fafafa;
            transition: all .2s;
            margin-bottom: 10px;
        }

        .tour-item:last-child { margin-bottom: 0; }

        .tour-item:hover {
            border-color: var(--primary-light);
            background: var(--primary-bg);
            box-shadow: var(--shadow-md);
        }

        .tour-item-left { display: flex; align-items: center; gap: 14px; flex: 1; min-width: 0; }

        .tour-thumb {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, var(--primary-light), #34d399);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .tour-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tour-meta {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .tour-meta span { display: flex; align-items: center; gap: 4px; }

        .tour-item-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

        /* ── TABLE ── */
        .table-wrap { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; font-size: 14px; }

        thead tr { background: #f8fafc; }

        th {
            text-align: left;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 2px solid var(--border);
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }

        tbody tr:hover { background: #f8fafc; }
        tbody tr:last-child td { border-bottom: none; }

        /* ── ALERT ── */
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

        /* ── MODAL ── */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15,23,42,.5);
            z-index: 200;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .modal-overlay.active { display: flex; }

        .modal-box {
            background: #fff;
            border-radius: 16px;
            padding: 28px;
            width: 90%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            animation: slideUp .2s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── FORM ── */
        .form-group { margin-bottom: 16px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            background: #fff;
            transition: border-color .2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(16,185,129,.1);
        }

        textarea.form-control { resize: vertical; min-height: 90px; }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-muted);
        }

        .empty-icon { font-size: 48px; margin-bottom: 12px; opacity: .5; }
        .empty-text { font-size: 15px; }

        /* ── BACK LINK ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 20px;
            transition: color .2s;
        }

        .back-link:hover { color: var(--primary); }

        /* ── DIVIDER ── */
        .divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .content { padding: 16px; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .tour-item { flex-direction: column; align-items: flex-start; }
            .tour-item-right { width: 100%; justify-content: flex-end; }
        }
    </style>
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('guide.dashboard') }}" class="brand-logo">
            <div class="brand-icon">🧭</div>
            <div>
                <div class="brand-name">TourViet</div>
                <div class="brand-sub">HDV Portal</div>
            </div>
        </a>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->username ?? 'G', 0, 2)) }}</div>
        <div style="min-width:0">
            <div class="user-name">{{ auth()->user()->fullname ?? auth()->user()->username }}</div>
            <div class="user-role">Hướng dẫn viên</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Tổng quan</div>
        <a href="{{ route('guide.dashboard') }}" class="nav-item {{ request()->routeIs('guide.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>

        <div class="nav-label">Tour của tôi</div>
        <a href="{{ route('guide.dashboard') }}#upcoming" class="nav-item">
            <span class="nav-icon">📅</span> Tour sắp tới
        </a>
        <a href="{{ route('guide.dashboard') }}#ongoing" class="nav-item">
            <span class="nav-icon">🚀</span> Đang diễn ra
        </a>
        <a href="{{ route('guide.dashboard') }}#completed" class="nav-item">
            <span class="nav-icon">✅</span> Đã hoàn thành
        </a>

        <div class="nav-label">Tài khoản</div>
        <a href="{{ route('guide.profile') }}" class="nav-item {{ request()->routeIs('guide.profile') ? 'active' : '' }}">
            <span class="nav-icon">👤</span> Hồ sơ cá nhân
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('guide.logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <span>🚪</span> Đăng xuất
            </button>
        </form>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <div>
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
            <div class="page-sub">@yield('page-sub', '')</div>
        </div>
        <div class="topbar-right">
            <span style="font-size:13px; color:var(--text-muted)">{{ now()->format('d/m/Y') }}</span>
        </div>
    </header>

    <main class="content">
        @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
