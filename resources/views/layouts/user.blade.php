<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VietTour - Khám Phá Việt Nam')</title>
    <meta name="description" content="@yield('meta_description', 'Đặt tour du lịch Việt Nam uy tín, giá tốt. Hàng trăm tour hấp dẫn đang chờ bạn!')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #0066cc;
            --primary-dark: #004fa3;
            --primary-light: #e8f2ff;
            --accent: #ff6b2b;
            --accent-dark: #e55a1f;
            --success: #10b981;
            --text-dark: #1a1a2e;
            --text-mid: #4a5568;
            --text-light: #718096;
            --bg-light: #f7faff;
            --bg-white: #ffffff;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.08);
            --shadow-md: 0 4px 16px rgba(0,0,0,.1);
            --shadow-lg: 0 10px 40px rgba(0,0,0,.15);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --transition: .25s cubic-bezier(.4,0,.2,1);
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background: var(--bg-white);
            line-height: 1.6;
        }
        a { text-decoration: none; color: inherit; }
        img { display: block; max-width: 100%; }
        button { cursor: pointer; border: none; font-family: inherit; }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 3px; }

        /* ===== UTILITIES ===== */
        .container { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 28px; border-radius: 50px;
            font-weight: 600; font-size: .9rem;
            transition: all var(--transition);
        }
        .btn-primary {
            background: var(--primary); color: #fff;
        }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,102,204,.35); }
        .btn-accent {
            background: var(--accent); color: #fff;
        }
        .btn-accent:hover { background: var(--accent-dark); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(255,107,43,.35); }
        .btn-outline {
            background: transparent; color: var(--primary);
            border: 2px solid var(--primary);
        }
        .btn-outline:hover { background: var(--primary); color: #fff; }
        .section-title { font-size: 2rem; font-weight: 800; color: var(--text-dark); margin-bottom: 8px; }
        .section-sub { color: var(--text-light); font-size: 1rem; margin-bottom: 40px; }
        .badge {
            display: inline-block; padding: 4px 12px; border-radius: 50px;
            font-size: .75rem; font-weight: 600;
        }
        .badge-blue { background: var(--primary-light); color: var(--primary); }
        .badge-orange { background: #fff3ee; color: var(--accent); }
        .badge-green { background: #ecfdf5; color: var(--success); }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(12px);
            box-shadow: var(--shadow-sm);
            transition: all var(--transition);
        }
        .navbar-inner {
            display: flex; align-items: center; justify-content: space-between;
            height: 68px;
        }
        .logo {
            display: flex; align-items: center; gap: 10px;
            font-size: 1.4rem; font-weight: 800; color: var(--primary);
        }
        .logo-icon {
            width: 38px; height: 38px; background: var(--primary); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-icon i { color: #fff; font-size: 1rem; }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .nav-links a {
            padding: 8px 16px; border-radius: 8px;
            font-weight: 500; font-size: .9rem; color: var(--text-mid);
            transition: all var(--transition);
        }
        .nav-links a:hover, .nav-links a.active { color: var(--primary); background: var(--primary-light); }
        .nav-auth { display: flex; align-items: center; gap: 10px; }
        .nav-auth a { font-weight: 600; font-size: .875rem; }
        .nav-auth .login-btn {
            padding: 8px 20px; border-radius: 8px;
            color: var(--primary); border: 1.5px solid var(--primary);
            transition: all var(--transition);
        }
        .nav-auth .login-btn:hover { background: var(--primary); color: #fff; }

        /* ===== MAIN CONTENT ===== */
        main { padding-top: 68px; }

        /* ===== CARD TOUR ===== */
        .tour-card {
            background: var(--bg-white);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            transition: all var(--transition);
        }
        .tour-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: transparent;
        }
        .tour-card-img {
            position: relative; overflow: hidden;
            height: 210px; background: #e2e8f0;
        }
        .tour-card-img img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform .5s ease;
        }
        .tour-card:hover .tour-card-img img { transform: scale(1.08); }
        .tour-card-img-placeholder {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex; align-items: center; justify-content: center;
        }
        .tour-card-img-placeholder i { font-size: 2.5rem; color: rgba(255,255,255,.5); }
        .tour-card-overlay {
            position: absolute; top: 12px; left: 12px;
            display: flex; gap: 6px; flex-wrap: wrap;
        }
        .tour-card-body { padding: 18px; }
        .tour-card-category { font-size: .75rem; color: var(--primary); font-weight: 600; margin-bottom: 6px; }
        .tour-card-name {
            font-size: 1rem; font-weight: 700; color: var(--text-dark);
            margin-bottom: 10px; line-height: 1.4;
            display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .tour-card-meta {
            display: flex; gap: 14px; margin-bottom: 14px;
        }
        .tour-card-meta span {
            display: flex; align-items: center; gap: 5px;
            font-size: .8rem; color: var(--text-light);
        }
        .tour-card-meta span i { color: var(--primary); font-size: .75rem; }
        .tour-card-footer {
            display: flex; align-items: center; justify-content: space-between;
            padding-top: 14px; border-top: 1px solid var(--border);
        }
        .tour-price { font-size: 1.1rem; font-weight: 800; color: var(--accent); }
        .tour-price small { font-size: .7rem; font-weight: 500; color: var(--text-light); display: block; }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--text-dark); color: #a0aec0;
            padding: 60px 0 20px;
        }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .footer-brand-name { color: #fff; font-size: 1.3rem; font-weight: 800; margin-bottom: 12px; }
        .footer-desc { font-size: .875rem; line-height: 1.8; margin-bottom: 20px; }
        .footer-social { display: flex; gap: 10px; }
        .footer-social a {
            width: 36px; height: 36px; border-radius: 8px;
            background: rgba(255,255,255,.08); color: #a0aec0;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; transition: all var(--transition);
        }
        .footer-social a:hover { background: var(--primary); color: #fff; }
        .footer-title { color: #fff; font-weight: 700; font-size: .9rem; margin-bottom: 16px; }
        .footer-links { display: flex; flex-direction: column; gap: 8px; }
        .footer-links a { font-size: .85rem; transition: color var(--transition); }
        .footer-links a:hover { color: #fff; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,.08); padding-top: 20px; text-align: center; font-size: .8rem; }

        /* ===== FLASH MESSAGES ===== */
        .alert {
            padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px; font-size: .9rem;
        }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .section-title { font-size: 1.5rem; }
        }
    </style>

    @yield('styles')
    @stack('styles')
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container">
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="logo">
                <div class="logo-icon"><i class="fas fa-plane"></i></div>
                VietTour
            </a>
            <div class="nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Trang Chủ
                </a>
                <a href="{{ route('tours.index') }}" class="{{ request()->routeIs('tours.*') ? 'active' : '' }}">
                    <i class="fas fa-map-marked-alt"></i> Danh Sách Tour
                </a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
                    <i class="fas fa-info-circle"></i> Giới Thiệu
                </a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i> Liên Hệ
                </a>
            </div>
            <div class="nav-auth">
                @auth
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <span style="font-size: 0.85rem; color: var(--text-mid); font-weight: 600;">
                            Chào, {{ Auth::user()->fullname }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="login-btn" style="background: transparent; font-size: 0.8rem; padding: 6px 14px;">
                                <i class="fas fa-sign-out-alt"></i> Thoát
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" style="color: var(--text-mid); margin-right: 15px;">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="login-btn">
                         Đăng ký
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main>
    @if(session('success'))
        <div class="container" style="padding-top:20px">
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container" style="padding-top:20px">
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        </div>
    @endif

    @yield('content')
</main>

<!-- FOOTER -->
<footer class="footer" id="footer-contact">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-brand-name">✈ VietTour</div>
                <p class="footer-desc">Chúng tôi mang đến những hành trình đáng nhớ khắp mọi miền Việt Nam. Uy tín - Chất lượng - Giá tốt.</p>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            <div>
                <div class="footer-title">Tour Du Lịch</div>
                <div class="footer-links">
                    <a href="{{ route('tours.index') }}">Tour Miền Bắc</a>
                    <a href="{{ route('tours.index') }}">Tour Miền Trung</a>
                    <a href="{{ route('tours.index') }}">Tour Miền Nam</a>
                    <a href="{{ route('tours.index') }}">Tour Quốc Tế</a>
                </div>
            </div>
            <div>
                <div class="footer-title">Hỗ Trợ</div>
                <div class="footer-links">
                    <a href="{{ route('about') }}">Giới thiệu</a>
                    <a href="{{ route('contact') }}">Liên hệ</a>
                    <a href="#">Chính sách hoàn tiền</a>
                    <a href="#">Điều khoản sử dụng</a>
                    <a href="#">FAQs</a>
                </div>
            </div>
            <div>
                <div class="footer-title">Liên Hệ</div>
                <div class="footer-links">
                    <a href="tel:1900xxxx"><i class="fas fa-phone"></i> 1900 xxxx</a>
                    <a href="mailto:info@viettour.vn"><i class="fas fa-envelope"></i> info@viettour.vn</a>
                    <a href="{{ route('contact') }}"><i class="fas fa-paper-plane"></i> Gửi tin nhắn</a>
                    <a href="#"><i class="fas fa-map-marker-alt"></i> Hà Nội, Việt Nam</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 VietTour. Đồ án tốt nghiệp - Website Đặt Tour Du Lịch.</p>
        </div>
    </div>
</footer>

@yield('scripts')
@stack('scripts')
</body>
</html>
