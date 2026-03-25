<!-- Topbar Bắt đầu -->
<div class="topbar-custom">
    <div class="container-xxl">
        <div class="d-flex justify-content-between">
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <button class="button-toggle-menu nav-link ps-0">
                        <i data-feather="menu" class="noti-icon"></i>
                    </button>
                </li>
                <li class="d-none d-lg-block">
                    <div class="position-relative topbar-search">
                        <input type="text" class="form-control bg-light bg-opacity-75 border-light ps-4"
                            placeholder="Tìm kiếm...">
                        <i
                            class="mdi mdi-magnify fs-16 position-absolute text-muted top-50 translate-middle-y ms-2"></i>
                    </div>
                </li>
            </ul>

            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">

                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link" data-toggle="fullscreen">
                        <i data-feather="maximize" class="align-middle fullscreen noti-icon"></i>
                    </button>
                </li>

                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        <i data-feather="bell" class="noti-icon"></i>
                        <span
                            class="badge bg-danger rounded-circle noti-icon-badge">1</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-lg">
                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0">
                                <span class="float-end">
                                    <form action="#" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="text-dark bg-transparent border-0 p-0"
                                            style="font-size: small;">
                                            Đánh dấu là đã đọc
                                        </button>
                                    </form>
                                </span>Thông báo
                            </h5>
                        </div>

                        <div class="noti-scroll" data-simplebar>
                            {{-- @php
                                $notifications = App\Models\Notification::where('is_read', false)->latest()->take(5)->get();
                                $notificationData = [];

                                foreach ($notifications as $notification) {
                                    $data = null;
                                    $route = '';
                                    $message = '';
                                    $avatar = asset('assets/admin/images/users/default.jpg');
                                    $name = '';

                                    if ($notification->type === 'user') {
                                        $data = App\Models\User::find($notification->data_id);
                                        if ($data && $data->vai_tro === 'user') {
                                            $route = route('admin.taikhoans.show', $data->id);
                                            $message = 'Người dùng mới đăng ký';
                                            $avatar = $data->anh_dai_dien ? asset('storage/' . $data->anh_dai_dien) : $avatar;
                                            $name = $data->ten;
                                        }
                                    } elseif ($notification->type === 'danh_gia') {
                                        $data = App\Models\DanhGiaSanPham::with(['user', 'sanPham'])->find($notification->data_id);
                                        if ($data) {
                                            $route = route('admin.Danhgias.show', $data->id);
                                            $message = 'Đánh giá mới cho sản phẩm <span class="text-reset">' . ($data->sanPham->ten_san_pham ?? 'Sản phẩm') . '</span>';
                                            $avatar = $data->user->anh_dai_dien ? asset('storage/' . $data->user->anh_dai_dien) : $avatar;
                                            $name = $data->user->ten;
                                        }
                                    } elseif ($notification->type === 'hoa_don') {
                                        $data = App\Models\HoaDon::with('user')->find($notification->data_id);
                                        if ($data) {
                                            $route = route('admin.hoadons.show', $data->id);
                                            $message = 'Đơn hàng mới #' . $data->ma_hoa_don;
                                            $avatar = $data->user->anh_dai_dien ? asset('storage/' . $data->user->anh_dai_dien) : $avatar;
                                            $name = $data->user->ten;
                                        }
                                    } elseif ($notification->type === 'lien_he') {
                                        $data = App\Models\lien_hes::with('user')->find($notification->data_id);
                                        if ($data) {
                                            $route = route('admin.lienhes.form.reply', $data->id);
                                            $message = 'Liên hệ mới từ ' . $data->ten_nguoi_gui;
                                            $avatar = $data->user->anh_dai_dien ? asset('storage/' . $data->user->anh_dai_dien) : $avatar;
                                            $name = $data->ten_nguoi_gui;
                                        }
                                    }

                                    if ($data) {
                                        $notificationData[] = [
                                            'route' => $route,
                                            'message' => $message,
                                            'avatar' => $avatar,
                                            'name' => $name,
                                            'created_at' => $notification->created_at,
                                        ];
                                    }
                                }
                            @endphp --}}

                            {{-- @foreach($notificationData as $notification)
                                <a href="{{ $notification['route'] }}"
                                    class="dropdown-item notify-item text-muted link-primary">
                                    <div class="notify-icon">
                                        <img src="{{ $notification['avatar'] }}" class="img-fluid rounded-circle" alt="" />
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="notify-details">{{ $notification['name'] }}</p>
                                        <small class="text-muted">{{ $notification['created_at']->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 user-msg">
                                        <small class="fs-14">{!! $notification['message'] !!}</small>
                                    </p>
                                </a>
                            @endforeach --}}
                        </div>

                        <!-- Tất cả -->
                        <a href="#"
                            class="dropdown-item text-center text-primary notify-item notify-all">
                            Xem tất cả
                            <i class="fe-arrow-right"></i>
                        </a>
                    </div>
                </li>

                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        {{-- @if (Auth::check() && Auth::user()->anh_dai_dien != '')
                            <img src="{{ asset('storage/' . Auth::user()->anh_dai_dien) }}" alt="user-image"
                                class="rounded-circle">
                        @else
                            <img src="{{ asset('assets/admin/images/users/user-11.jpg') }}" alt="user-image"
                                class="rounded-circle">
                        @endif
                        <span class="pro-user-name ms-1">
                            @if (Auth::check())
                                {{ Auth::user()->ten }}
                            @else
                                Guest
                            @endif
                            <i class="mdi mdi-chevron-down"></i>
                        </span> --}}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">
                                {{-- @if (Auth::check())
                                    Chào mừng, {{ Auth::user()->ten }}!
                                @else
                                    Chào mừng, Khách!
                                @endif --}}
                            </h6>
                        </div>

                        <!-- item-->
                        @if (Auth::check())
                            <a class="dropdown-item notify-item" href="#">
                                <i class="mdi mdi-account-circle-outline fs-16 align-middle"></i>
                                <span>Tài khoản của tôi</span>
                            </a>
                        @else
                            <a class="dropdown-item notify-item" href="#">
                                <i class="mdi mdi-login fs-16 align-middle"></i>
                                <span>Đăng nhập</span>
                            </a>
                        @endif


                        <!-- item-->
                        <a class='dropdown-item notify-item' href='auth-lock-screen.html'>
                            <i class="mdi mdi-lock-outline fs-16 align-middle"></i>
                            <span>Màn hình khóa</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- item-->
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>

                        <a class='dropdown-item notify-item' href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                            <span>Đăng xuất</span>
                        </a>


                    </div>
                </li>

            </ul>
        </div>

    </div>

</div>
<!-- end Topbar -->