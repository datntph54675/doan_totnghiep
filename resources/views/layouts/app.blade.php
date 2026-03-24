<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        /* Sidebar Styles */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            min-height: 100vh;
            background: #1e293b;
            /* Dark Slate */
            color: #fff;
            transition: all 0.3s;
            position: fixed;
            z-index: 1000;
        }

        #sidebar .sidebar-header {
            padding: 25px 20px;
            background: #0f172a;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        #sidebar ul p {
            color: #64748b;
            padding: 10px 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 0;
        }

        #sidebar ul.components {
            padding: 15px 0;
        }

        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            color: #cbd5e1;
            text-decoration: none;
            transition: 0.3s;
        }

        #sidebar ul li a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: #334155;
        }

        #sidebar ul li.active>a {
            color: #fff;
            background: #3b82f6;
            /* Blue Accent */
            font-weight: 500;
        }

        /* Main Content Styles */
        #content {
            width: 100%;
            padding-left: 260px;
            /* Bằng chiều rộng sidebar */
            min-height: 100vh;
            transition: all 0.3s;
        }

        .top-navbar {
            background: #fff;
            padding: 15px 30px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 30px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Logout Button */
        .btn-logout {
            color: #f87171 !important;
            margin-top: 50px;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.1) !important;
        }

        @media (max-width: 992px) {
            #sidebar {
                margin-left: -260px;
            }

            #sidebar.active {
                margin-left: 0;
            }

            #content {
                padding-left: 0;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">Trang chủ</a>
                <a class="nav-link" href="{{ route('admin.categories.index') }}">Danh mục</a>
                <a class="nav-link" href="{{ route('admin.tours.index') }}">Tour</a>
                <a class="nav-link" href="{{ route('admin.users.index') }}">Khách hàng</a>
                <a class="nav-link" href="{{ route('admin.guides.index') }}">HDV</a>
                <a class="nav-link" href="{{ route('admin.guide-assignments.index') }}">Phân công HDV</a>
                <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link">Đăng xuất</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Thành công!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


    <div class="wrapper d-flex">
        <nav id="sidebar">
            <div class="sidebar-header border-bottom border-secondary">
                <i class="fas fa-shield-halved me-2 text-primary"></i> ADMIN PANEL
            </div>

            <ul class="list-unstyled components">
                <p>Menu chính</p>
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-chart-line"></i> Trang chủ
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-layer-group"></i> Danh mục
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.tours.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.tours.index') }}">
                        <i class="fas fa-route"></i> Quản lý Tour
                    </a>
                </li>

                <p>Hệ thống</p>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST" id="logout-form">
                        @csrf
                        <a href="javascript:void(0)" onclick="document.getElementById('logout-form').submit();"
                            class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </form>
                </li>
            </ul>
        </nav>

        <div id="content">
            <nav class="navbar top-navbar sticky-top">
                <div class="container-fluid">
                    <span class="navbar-text text-secondary fw-medium">
                        Hệ thống quản lý du lịch v1.0
                    </span>
                    <div class="d-flex align-items-center">
                        <div class="me-3 text-end d-none d-sm-block">
                            <small class="text-muted d-block">Xin chào,</small>
                            <span class="fw-bold">Quản trị viên</span>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff"
                            class="rounded-circle shadow-sm" width="40" height="40">
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Thành công!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Lỗi!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="fas fa-triangle-exclamation me-2"></i>
                        <strong>Kiểm tra lại dữ liệu:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="py-2">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>