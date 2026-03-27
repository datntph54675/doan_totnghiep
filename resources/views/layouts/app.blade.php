<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from zoyothemes.com/tapeli/html/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 16 Jul 2024 08:33:02 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>

    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc." />
    <meta name="author" content="Zoyothemes" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/admin/images/favicon.ico') }}">
    <!-- App css -->
    <link href="{{ asset('assets/admin/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons -->
    <link href="{{ asset('assets/admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite(['resources/js/app.js'])
    @yield('css')
</head>

<!-- body start -->

<body data-menu-color="light" data-sidebar="default">

    <!-- Begin page -->
    <div id="app-layout">

        @include('admin.block.header')

        @include('admin.block.sidebar')

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <!-- Flash Messages -->
            <br>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')

            @include('admin.block.footer')

        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Vendor -->
    <script src="{{ asset('assets/admin/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/feather-icons/feather.min.js') }}"></script>

    <!-- Apexcharts JS -->
    <script src="{{ asset('assets/admin/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- for basic area chart -->
    <script src="{{ asset('assets/admin/apexcharts.com/samples/assets/stock-prices.js') }}"></script>
    <script type="module">
        let currentRoomId = null;
        const currentUserId = {{ auth()->id() }};

        window.Echo.join('staff-presence')
            .here(users => {
                startStaffPing();
            })
            .joining(user => {
                if (user.id !== currentUserId) {
                    axios.post('/admin/set-admin-online', {
                        user_id: user.id
                    });
                }
            })
            .leaving(user => {
                if (user.id !== currentUserId) {
                    axios.post('/admin/set-admin-offline', {
                        user_id: user.id
                    });
                }
            });
        let staffPingInterval;

        function startStaffPing() {
            clearInterval(staffPingInterval); // nếu có sẵn thì clear
            staffPingInterval = setInterval(() => {
                axios.post('/admin/ping-online');
            }, 10000); // ping mỗi 10 giây
        }

        window.Echo.private(`user.${currentUserId}`)
            .listen('.SendMessage', (e) => {


                const roomId = e.message.chat_room_id;
                if (roomId !== currentRoomId) {
                    incrementUnread(roomId, e.message.sender.email);

                }
                console.log(e.unreadCount);

                const totalBadge = document.querySelector('#total-unread-badge');
                if (totalBadge) {
                    totalBadge.textContent = e.unreadCount > 0 ? e.unreadCount : '';
                    totalBadge.style.display = e.unreadCount > 0 ? 'inline-block' : 'none';
                }
            });

        function incrementUnread(roomId, senderEmail) {
            const roomElement = document.querySelector(`#room-${roomId}`);

            if (roomElement) {
                // Đã tồn tại, cập nhật badge
                const badge = roomElement.querySelector('.unread-badge');
                if (badge) {
                    let currentCount = parseInt(badge.textContent || '0');
                    currentCount++;
                    badge.textContent = currentCount;
                    badge.style.display = 'inline-block';
                }
            } else {
                // Nếu chưa có phòng này trong danh sách, tạo mới <li>
                const li = document.createElement('li');
                li.id = `room-${roomId}`;
                li.setAttribute('onclick', `selectRoom(${roomId}, '${senderEmail}')`);
                li.innerHTML = `
            ${senderEmail}
            <span class="unread-badge"
                style="display:inline-block; background:red; border-radius:50%; padding:3px 6px; color:white; font-size:12px;">
                1
            </span>
        `;

                const list = document.querySelector('#chatRoomList'); // ul chứa các <li>
                if (list) {
                    list.prepend(li); // Thêm vào đầu danh sách
                }
            }
        }
    </script>
    @yield('js')

    <!-- App js-->
    <script src="{{ asset('assets/admin/js/app.js') }}"></script>

</body>

<!-- Mirrored from zoyothemes.com/tapeli/html/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 16 Jul 2024 08:34:03 GMT -->

</html>
