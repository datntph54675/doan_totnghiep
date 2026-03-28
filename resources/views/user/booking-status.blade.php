@extends('layouts.user')

@section('title', 'Trạng thái booking - VietTour')

@section('styles')
<style>
    :root {
        --primary-blue: #0066cc;
        --secondary-blue: #004d99;
        --text-dark: #2d3436;
        --text-gray: #636e72;
        --bg-light: #f8fafc;
        --white: #ffffff;
        --danger: #d63031;
    }

    .booking-status-page {
        background: var(--bg-light);
        min-height: calc(100vh - 80px);
        padding: 50px 0;
    }

    .container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .page-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 6px;
    }

    .page-subtitle {
        color: var(--text-gray);
        font-size: 15px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 18px;
        border-radius: 12px;
        background: var(--white);
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 700;
        border: 1px solid #dbe7f5;
    }

    .content-card {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 32px;
        margin-bottom: 24px;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 14px;
        margin-bottom: 20px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        background: #e6fffa;
        color: #2c7a7b;
        border: 1px solid #b2f5ea;
    }

    .alert-danger {
        background: #fff5f5;
        color: #c53030;
        border: 1px solid #feb2b2;
    }

    .booking-section-title {
        margin: 0 0 14px;
        font-size: 18px;
        font-weight: 800;
        color: var(--text-dark);
    }

    .booking-section+.booking-section {
        margin-top: 28px;
        padding-top: 28px;
        border-top: 1px solid #edf2f7;
    }

    .booking-list {
        display: grid;
        gap: 16px;
    }

    .booking-item {
        border: 1px solid #e9eef5;
        border-radius: 14px;
        padding: 16px;
        background: #fcfdff;
    }

    .booking-item-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        flex-wrap: wrap;
    }

    .booking-title {
        margin: 0;
        font-size: 17px;
        color: var(--text-dark);
        font-weight: 700;
    }

    .status-confirmed,
    .status-pending,
    .status-unpaid,
    .status-cancelled,
    .status-refunded {
        border-radius: 999px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-confirmed {
        background: #dcfce7;
        color: #166534;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-unpaid {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-refunded {
        background: #ede9fe;
        color: #5b21b6;
    }

    .booking-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px 16px;
        color: var(--text-gray);
        font-size: 14px;
        margin: 8px 0 12px;
    }

    .booking-price {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary-blue);
    }

    .booking-link {
        text-decoration: none;
        font-weight: 600;
        color: var(--primary-blue);
    }

    .booking-cancel-form {
        margin: 0;
    }

    .booking-cancel-btn {
        border: 1px solid #fca5a5;
        background: #fff5f5;
        color: #b91c1c;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .booking-cancel-btn:hover {
        background: #fee2e2;
    }

    .empty-state {
        border: 1px dashed #d8deea;
        background: #fbfcfe;
        padding: 20px;
        border-radius: 12px;
        color: var(--text-gray);
        font-size: 14px;
    }

    @media (max-width: 900px) {
        .booking-meta {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="booking-status-page">
    <div class="container">
        <div class="page-head">
            <div>
                <h1 class="page-title">Trạng thái Tour</h1>
                <p class="page-subtitle">Theo dõi toàn bộ booking của {{ $user->fullname }} tại đây.</p>
            </div>
            <a href="{{ route('user.profile') }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i>
                Quay lại hồ sơ
            </a>
        </div>

        @if (session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
        @endif

        <div class="content-card">
            <section class="booking-section">
                <h2 class="booking-section-title">Chờ thanh toán ({{ ($unpaidBookings ?? collect())->count() }})</h2>
                @if (($unpaidBookings ?? collect())->isEmpty())
                <div class="empty-state">Hiện không có booking nào đang chờ thanh toán.</div>
                @else
                <div class="booking-list">
                    @foreach ($unpaidBookings as $booking)
                    <article class="booking-item">
                        <div class="booking-item-head">
                            <h3 class="booking-title">{{ $booking->tour->name ?? 'Tour không xác định' }}</h3>
                            <span class="status-unpaid">Chờ thanh toán</span>
                        </div>

                        <div class="booking-meta">
                            <div>Mã booking: #{{ $booking->booking_id }}</div>
                            <div>Ngày đặt: {{ optional($booking->booking_date)->format('d/m/Y H:i') }}</div>
                            <div>Khởi hành: {{ optional(optional($booking->schedule)->start_date)->format('d/m/Y') ?? '-' }}</div>
                            <div>Số người: {{ $booking->num_people }} người</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="booking-price">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</div>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <a class="booking-link" href="{{ route('payment.choose', $booking->booking_id) }}">Thanh toán ngay</a>
                                @if ($booking->tour_id)
                                <a class="booking-link" href="{{ route('tours.show', $booking->tour_id) }}">Xem tour</a>
                                @endif
                                @if ($booking->canBeCancelledByUser())
                                <form class="booking-cancel-form" action="{{ route('user.booking.cancel', $booking->booking_id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy booking này không?')">
                                    @csrf
                                    <button type="submit" class="booking-cancel-btn">Hủy booking</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif
            </section>

            <section class="booking-section">
                <h2 class="booking-section-title">Chờ xác nhận ({{ ($pendingBookings ?? collect())->count() }})</h2>
                @if (($pendingBookings ?? collect())->isEmpty())
                <div class="empty-state">Hiện không có booking nào đang chờ xác nhận.</div>
                @else
                <div class="booking-list">
                    @foreach ($pendingBookings as $booking)
                    <article class="booking-item">
                        <div class="booking-item-head">
                            <h3 class="booking-title">{{ $booking->tour->name ?? 'Tour không xác định' }}</h3>
                            <span class="status-pending">Chờ xác nhận</span>
                        </div>

                        <div class="booking-meta">
                            <div>Mã booking: #{{ $booking->booking_id }}</div>
                            <div>Ngày đặt: {{ optional($booking->booking_date)->format('d/m/Y H:i') }}</div>
                            <div>Khởi hành: {{ optional(optional($booking->schedule)->start_date)->format('d/m/Y') ?? '-' }}</div>
                            <div>Số người: {{ $booking->num_people }} người</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="booking-price">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</div>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                @if ($booking->tour_id)
                                <a class="booking-link" href="{{ route('tours.show', $booking->tour_id) }}">Xem tour</a>
                                @endif
                                @if ($booking->canBeCancelledByUser())
                                <form class="booking-cancel-form" action="{{ route('user.booking.cancel', $booking->booking_id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy booking này không?')">
                                    @csrf
                                    <button type="submit" class="booking-cancel-btn">Hủy booking</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif
            </section>

            <section class="booking-section">
                <h2 class="booking-section-title">Đã xác nhận ({{ ($confirmedBookings ?? collect())->count() }})</h2>
                @if (($confirmedBookings ?? collect())->isEmpty())
                <div class="empty-state">Hiện chưa có tour nào được admin xác nhận.</div>
                @else
                <div class="booking-list">
                    @foreach ($confirmedBookings as $booking)
                    <article class="booking-item">
                        <div class="booking-item-head">
                            <h3 class="booking-title">{{ $booking->tour->name ?? 'Tour không xác định' }}</h3>
                            <span class="status-confirmed">Đã xác nhận</span>
                        </div>

                        <div class="booking-meta">
                            <div>Mã booking: #{{ $booking->booking_id }}</div>
                            <div>Ngày đặt: {{ optional($booking->booking_date)->format('d/m/Y H:i') }}</div>
                            <div>Khởi hành: {{ optional(optional($booking->schedule)->start_date)->format('d/m/Y') ?? '-' }}</div>
                            <div>Số người: {{ $booking->num_people }} người</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="booking-price">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</div>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                @if ($booking->tour_id)
                                <a class="booking-link" href="{{ route('tours.show', $booking->tour_id) }}">Xem tour</a>
                                @endif
                                @if ($booking->canBeCancelledByUser())
                                <form class="booking-cancel-form" action="{{ route('user.booking.cancel', $booking->booking_id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy booking này không?')">
                                    @csrf
                                    <button type="submit" class="booking-cancel-btn">Hủy booking</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif
            </section>

            <section class="booking-section">
                <h2 class="booking-section-title">Đã hủy ({{ ($cancelledBookings ?? collect())->count() }})</h2>
                @if (($cancelledBookings ?? collect())->isEmpty())
                <div class="empty-state">Bạn chưa hủy booking nào.</div>
                @else
                <div class="booking-list">
                    @foreach ($cancelledBookings as $booking)
                    <article class="booking-item">
                        <div class="booking-item-head">
                            <h3 class="booking-title">{{ $booking->tour->name ?? 'Tour không xác định' }}</h3>
                            <span class="status-cancelled">Đã hủy</span>
                        </div>

                        <div class="booking-meta">
                            <div>Mã booking: #{{ $booking->booking_id }}</div>
                            <div>Ngày đặt: {{ optional($booking->booking_date)->format('d/m/Y H:i') }}</div>
                            <div>Khởi hành: {{ optional(optional($booking->schedule)->start_date)->format('d/m/Y') ?? '-' }}</div>
                            <div>Thanh toán:
                                @if ($booking->payment_status === 'refunded')
                                Đã hoàn tiền
                                @elseif($booking->payment_status === 'paid')
                                Đã thanh toán
                                @else
                                Chưa thanh toán
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="booking-price">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</div>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                @if ($booking->payment_status === 'refunded')
                                <span class="status-refunded">Đã hoàn tiền</span>
                                @endif
                                @if ($booking->tour_id)
                                <a class="booking-link" href="{{ route('tours.show', $booking->tour_id) }}">Xem tour</a>
                                @endif
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection