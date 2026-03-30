<!-- Left Sidebar Start -->
<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>
        <!-- Sidebar Menu -->
        <div id="sidebar-menu">
            <!-- Logo Box -->
            <div class="logo-box">
                <a href="{{ route('home') }}">
                    @if(Route::is('momo.mock'))
                        <img src="https://developers.momo.vn/v2/images/logo.png" alt="logo"
                            style="width:70px; height:70px; padding: 5px;">
                    @else
                        <img src="{{ asset('assets/admin/images/logo-dark.png') }}" alt="logo"
                            style="width:160px; height:70px;">
                    @endif
                </a>
            </div>
            <!-- End Logo Box -->

            <ul id="side-menu">

                <!-- Quản trị -->

                <li class="menu-title">Quản trị</li>


                {{-- Thống kê --}}
                <li>
                    <a href='#dbs' data-bs-toggle="collapse">
                        <i data-feather="home"></i>
                        <span> Thống kê </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="dbs">
                        <ul class="nav-second-level">
                            <li>
                                <a href="#" class="tp-link">Dashboard</a>
                            </li>
                            <li>
                                <a class='tp-link' href="#">Thống kê 1</a>
                            </li>
                            <li>
                                <a class='tp-link' href="#">Thống kê 2</a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Quản lý tài khoản --}}
                <li>
                    <a href="#sidebarTables" data-bs-toggle="collapse">
                        <i data-feather="users"></i>
                        <span> Quản lý tài khoản </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarTables">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='{{ route('admin.guides.index') }}'>HDV</a>
                            </li>

                            <li>
                                <a class='tp-link' href='{{ route('admin.users.index') }}'>Khách hàng</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Kinh doanh -->
                <li class="menu-title">Kinh doanh</li>

                {{-- Thông Báo --}}
                <li>
                    <a href="#chat" data-bs-toggle="collapse">
                        <i class="fa fa-comments"></i>
                        <span> Thông Báo </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="chat">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href="#">Danh sách</a>
                            </li>

                        </ul>
                    </div>
                </li>

                {{-- Hóa đơn --}}
                <li>
                    <a href='#hoadons' data-bs-toggle="collapse">
                        <i data-feather="shopping-bag"></i>
                        <span> Hóa đơn </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="hoadons">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href="#">Danh sách</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <!-- Booking -->
                <li>
                    <a href="#booking" data-bs-toggle="collapse">
                        <i data-feather="clipboard"></i>
                        <span> Booking </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="booking">
                        <ul class="nav-second-level">
                            <li><a class='tp-link' href="{{ route('admin.bookings.index') }}">Danh sách</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Tour -->
                <li>
                    <a href="#tour" data-bs-toggle="collapse">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M18 3a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3H6a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3V6a3 3 0 0 0-3-3 3 3 0 0 0-3 3 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 3 3 0 0 0-3-3z">
                            </path>
                        </svg>
                        <span> Tour </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="tour">
                        <ul class="nav-second-level">
                            <li><a class='tp-link' href="{{ route('admin.tours.index') }}">Danh sách</a></li>
                        </ul>
                    </div>
                </li>
                <!-- Phân công HDV -->
                <li>
                    <a href="#guide_assignment" data-bs-toggle="collapse">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M18 3a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3H6a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3V6a3 3 0 0 0-3-3 3 3 0 0 0-3 3 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 3 3 0 0 0-3-3z">
                            </path>
                        </svg>
                        <span> Phân công HDV </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="guide_assignment">
                        <ul class="nav-second-level">
                            <li><a class='tp-link' href="{{ route('admin.guide-assignments.index') }}">Danh sách</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Danh mục -->
                <li>
                    <a href="#categories" data-bs-toggle="collapse">
                        <i data-feather="list"></i>
                        <span> Danh mục </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="categories">
                        <ul class="nav-second-level">
                            <li><a class='tp-link' href="{{ route('admin.categories.index') }}">Danh sách</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Banner -->
                <li>
                    <a href="#bannerSection" data-bs-toggle="collapse">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect>
                            <line x1="7" y1="2" x2="7" y2="22"></line>
                            <line x1="17" y1="2" x2="17" y2="22"></line>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <line x1="2" y1="7" x2="7" y2="7"></line>
                            <line x1="2" y1="17" x2="7" y2="17"></line>
                            <line x1="17" y1="17" x2="22" y2="17"></line>
                            <line x1="17" y1="7" x2="22" y2="7"></line>
                        </svg>
                        <span> Banner </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="bannerSection">
                        <ul class="nav-second-level">
                            <li><a class='tp-link' href="#">Danh sách</a></li>
                            <li><a class='tp-link' href="#">Thêm mới</a></li>
                        </ul>
                    </div>
                </li>


                <!-- Tin tức -->
                <li>
                    <a href="#baiviets" data-bs-toggle="collapse">
                        <i data-feather="table"></i>
                        <span>Tin tức</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="baiviets">
                        <ul class="nav-second-level">
                            <li><a class='tp-link' href="#">Danh sách</a></li>
                            <li><a class='tp-link' href="#">Thêm mới</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Khuyến mãi -->
                <li>
                    <a href="#promotionSection" data-bs-toggle="collapse">
                        <i class="fas fa-tag"></i>
                        <span> Khuyến mãi </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="promotionSection">
                        <ul class="nav-second-level">
                            <li><a class='tp-link' href="#">Danh sách</a></li>
                            <li><a class='tp-link' href="#">Thêm mới</a></li>
                            <li><a class='tp-link' href="#">Thùng rác <i class="fas fa-trash-alt"></i> </a></li>
                        </ul>
                    </div>
                </li>

                <!-- Đánh giá -->
                <li>
                    <a href="#feedbackSection" data-bs-toggle="collapse">
                        <i class="fa-solid fa-thumbs-up"></i>
                        <span> Đánh giá </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="feedbackSection">
                        <ul class="nav-second-level">
                            <li><a class='tp-link' href="{{ route('admin.feedback.index') }}">Danh sách</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Phản Hồi Từ HDV -->
                <li>
                    <a href="#guideFeedbackSection" data-bs-toggle="collapse">
                        <i class="fa-solid fa-comments"></i>
                        <span> Phản Hồi HDV </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="guideFeedbackSection">
                        <ul class="nav-second-level">
                            <li><a class='tp-link' href="{{ route('admin.guide-feedback.index') }}">Danh sách</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Liên hệ --}}
                <li>

                    <a href="#lienhes" data-bs-toggle="collapse">
                        <i class="fa fa-phone"></i>
                        <span> Liên hệ </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="lienhes">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href="#">Danh sách</a>
                            </li>

                        </ul>
                    </div>
                </li>


            </ul>
        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End -->