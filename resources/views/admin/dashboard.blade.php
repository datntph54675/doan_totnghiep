<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển Admin</title>
    <link href="/css/app.css" rel="stylesheet">
    <style>
        :root {
            --bg: #e6f2ff;
            /* main blue background */
            --card: #ffffff;
            --accent: #0f62fe;
            /* primary blue */
            --accent-2: #0052cc;
            /* darker accent */
            --muted: #5b6b80;
            --sidebar-bg: #05224a;
            /* deep blue sidebar */
            --sidebar-text: #e6eefb;
            --glass: rgba(255, 255, 255, 0.7);
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial;
            background: var(--bg);
            color: #111
        }

        .container {
            max-width: 1200px;
            margin: 28px auto;
            padding: 22px;
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
            width: 56px;
            height: 56px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.14);
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 18px
        }

        .card {
            background: var(--card);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.06);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(2, 6, 23, 0.08);
        }

        /* Layout for sidebar + main */
        .layout {
            display: flex;
            gap: 22px;
            align-items: flex-start;
        }

        .sidebar {
            width: 240px;
            background: linear-gradient(180deg, var(--sidebar-bg), #02102b);
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 12px 40px rgba(2, 6, 23, 0.12);
            height: calc(100vh - 56px);
            position: sticky;
            top: 28px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            color: var(--sidebar-text);
        }

        .sidebar-header {
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 1px;
            color: rgba(230, 238, 251, 0.95);
        }

        .sidebar-user {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 8px;
            border-radius: 10px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.02), transparent);
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 12px;
        }

        .nav-item {
            padding: 10px 12px;
            border-radius: 10px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 600;
            display: block;
            transition: background .12s ease, transform .12s ease;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.03);
            transform: translateX(4px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
            color: #fff;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.14);
        }

        .sidebar-footer {
            margin-top: auto;
        }

        .main-content {
            flex: 1;
        }

        .stat {
            font-size: 28px;
            font-weight: 700
        }

        /* stat label with small caption */
        .card .muted {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 6px
        }

        .card .stat {
            display: flex;
            align-items: center;
            gap: 10px
        }

        .stat-badge {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: var(--sidebar-text);
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
        }

        .topbar .btn-logout {
            background: #fff;
            color: #111;
            border-color: #eef2f8
        }

        @media (max-width: 980px) {
            .container {
                padding: 14px
            }

            .layout {
                flex-direction: column
            }

            .sidebar {
                width: 100%;
                position: static;
                height: auto;
                order: 0
            }

            .main-content {
                order: 1
            }

            .mobile-toggle {
                display: inline-flex
            }
        }

        /* Mobile: sidebar overlay hidden by default, opened with .show */
        @media (max-width: 980px) {
            .sidebar {
                position: fixed;
                left: 16px;
                top: 76px;
                width: calc(100% - 32px);
                max-width: 360px;
                transform: translateX(-120%);
                opacity: 0;
                transition: transform .22s ease, opacity .22s ease;
                z-index: 90;
                box-shadow: 0 18px 50px rgba(2, 6, 23, 0.18);
            }

            .sidebar.show {
                transform: translateX(0);
                opacity: 1
            }

            .mobile-toggle {
                display: inline-flex;
                width: 44px;
                height: 44px;
                border-radius: 10px;
                align-items: center;
                justify-content: center;
                background: var(--card);
                border: none;
                box-shadow: 0 8px 24px rgba(2, 6, 23, 0.08);
                font-size: 18px;
                cursor: pointer;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="layout">
            <aside class="sidebar">
                <div class="sidebar-header">ADMIN</div>
                <div class="sidebar-user">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->username ?? 'AD',0,2)) }}</div>
                    <div>
                        <div style="font-weight:600">{{ auth()->user()->fullname ?? auth()->user()->username }}</div>
                        <div class="muted">Role: {{ auth()->user()->role }}</div>
                    </div>
                </div>

                <nav class="sidebar-nav">
                    <a href="#" class="nav-item active">Bảng điều khiển</a>
                    <a href="#" class="nav-item">Quản lý tour</a>
                    <a href="#" class="nav-item">Đơn đặt tour</a>
                    <a href="#" class="nav-item">Khách hàng</a>
                    <a href="#" class="nav-item">Hướng dẫn viên</a>
                </nav>

                <div class="sidebar-footer">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="btn-logout" type="submit">Đăng xuất</button>
                    </form>
                </div>
            </aside>

            <main class="main-content">
                <div class="topbar">
                    <div style="display:flex;align-items:center;gap:10px">
                        <button class="mobile-toggle" aria-label="Mở menu" aria-expanded="false">☰</button>
                        <div>
                            <div class="title">Bảng điều khiển Admin</div>
                            <div class="muted">Chào mừng, {{ auth()->user()->fullname ?? auth()->user()->username }}.</div>
                        </div>
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
                        <div class="stat">{{ \DB::table('tour')->count() }}</div>
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
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.querySelector('.mobile-toggle');
            var sidebar = document.querySelector('.sidebar');
            if (!btn || !sidebar) return;

            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                var open = sidebar.classList.toggle('show');
                btn.setAttribute('aria-expanded', String(open));
            });

            // close when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 980 && sidebar.classList.contains('show')) {
                    if (!sidebar.contains(e.target) && !btn.contains(e.target)) {
                        sidebar.classList.remove('show');
                        btn.setAttribute('aria-expanded', 'false');
                    }
                }
            });
        });
    </script>
</body>

</html>