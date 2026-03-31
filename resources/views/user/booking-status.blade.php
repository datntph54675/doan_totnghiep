@extends('layouts.user')

@section('title', 'Trạng thái booking - GoTour')

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
        padding: 40px 0 80px;
    }

    .container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .page-head {
        margin-top: 50px;
        /* Thêm margin để tránh dính Header fixed */
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
    .status-completed,
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

    .status-completed {
        background: #ede9fe;
        color: #6d28d9;
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

    .status-expired {
        background: #fff7ed;
        color: #9a3412;
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
        border-radius: 12px;
        padding: 10px 18px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .booking-cancel-btn:hover {
        background: #fee2e2;
        transform: translateY(-1px);
    }

    /* New Button Styles */
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-pay {
        background: var(--primary-blue);
        color: var(--white);
        box-shadow: 0 4px 12px rgba(0, 102, 204, 0.2);
    }

    .btn-pay:hover {
        background: var(--secondary-blue);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 102, 204, 0.3);
    }

    .btn-view {
        background: var(--white);
        color: var(--primary-blue);
        border: 1px solid #dbe7f5;
    }

    .btn-view:hover {
        background: #f0f7ff;
        border-color: var(--primary-blue);
        transform: translateY(-1px);
    }

    .booking-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .booking-price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px dashed #e2e8f0;
    }

    .payment-countdown {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #e53e3e;
        background: #fff5f5;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        border: 1px solid #feb2b2;
    }

    .payment-countdown i {
        font-size: 12px;
    }

    .empty-state {
        border: 1px dashed #d8deea;
        background: #fbfcfe;
        padding: 20px;
        border-radius: 12px;
        color: var(--text-gray);
        font-size: 14px;
    }

    .feedback-card {
        margin-top: 16px;
        padding: 16px;
        background: #f8fbff;
        border: 1px solid #dbe7f5;
        border-radius: 14px;
    }

    .feedback-title {
        margin: 0 0 10px;
        font-size: 15px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .feedback-form {
        display: grid;
        gap: 12px;
    }

    .feedback-field {
        display: grid;
        gap: 6px;
    }

    .feedback-field label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .feedback-input,
    .feedback-textarea {
        width: 100%;
        border: 1px solid #dbe7f5;
        border-radius: 12px;
        padding: 10px 12px;
        font-size: 14px;
        outline: none;
        background: #fff;
    }

    .feedback-input:focus,
    .feedback-textarea:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.1);
    }

    .feedback-textarea {
        min-height: 110px;
        resize: vertical;
    }

    .feedback-meta-box {
        display: grid;
        gap: 8px;
        color: var(--text-gray);
        font-size: 14px;
    }

    .feedback-stars {
        color: #f59e0b;
        letter-spacing: 2px;
        font-size: 15px;
    }

    /* ====== TABS SYSTEM ====== */
    .status-tabs-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        border-bottom: 2px solid #edf2f7;
        gap: 20px;
        flex-wrap: wrap;
        position: sticky;
        top: 68px; /* Stick below navbar if needed, or just let it flow */
        background: var(--white);
        z-index: 10;
        padding: 5px 0;
    }

    .status-tabs {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 0;
        scrollbar-width: none; /* Firefox */
    }
    .status-tabs::-webkit-scrollbar { display: none; } /* Chrome/Safari */

    .tab-btn {
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-gray);
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tab-btn:hover {
        color: var(--primary-blue);
        background: rgba(0, 102, 204, 0.05);
    }

    .tab-btn.active {
        color: var(--primary-blue);
        border-bottom-color: var(--primary-blue);
        background: rgba(0, 102, 204, 0.03);
    }

    .tab-count {
        font-size: 11px;
        background: #edf2f7;
        color: var(--text-gray);
        padding: 2px 8px;
        border-radius: 10px;
        transition: all 0.2s;
    }

    .active .tab-count {
        background: var(--primary-blue);
        color: #fff;
    }

    /* ====== SEARCH BOX ====== */
    .search-box-container {
        position: relative;
        min-width: 200px;
        flex: 1;
        max-width: 300px;
    }

    .search-input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s;
        background: #f8fafc;
    }

    .search-input:focus {
        border-color: var(--primary-blue);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-gray);
        pointer-events: none;
    }

    /* ====== HIDING SECTIONS ====== */
    .booking-section.hidden {
        display: none;
    }

    /* Tối ưu responsive cho Tab */
    @media (max-width: 768px) {
        .status-tabs-container {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            position: relative;
            top: 0;
        }
        .search-box-container {
            max-width: none;
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

        @if ($errors->any())
        <div class="alert alert-danger" style="display:block;">
            <div><i class="fa-solid fa-triangle-exclamation"></i> Vui lòng kiểm tra lại thông tin đánh giá:</div>
            <ul style="margin:10px 0 0 18px; padding:0;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="content-card">
            {{-- STATUS TABS --}}
            <div class="status-tabs-container">
                <div class="status-tabs">
                    <button class="tab-btn active" onclick="filterByStatus('all')">
                        <i class="fa-solid fa-list-ul"></i> Tất cả
                        <span class="tab-count">{{ ($unpaidBookings->count() + $pendingBookings->count() + $confirmedBookings->count() + $completedBookings->count() + $cancelledBookings->count()) }}</span>
                    </button>
                    <button class="tab-btn" onclick="filterByStatus('unpaid')">
                        <i class="fa-solid fa-credit-card"></i> Chờ thanh toán
                        <span class="tab-count">{{ $unpaidBookings->count() }}</span>
                    </button>
                    <button class="tab-btn" onclick="filterByStatus('pending')">
                        <i class="fa-solid fa-clock-rotate-left"></i> Chờ xác nhận
                        <span class="tab-count">{{ $pendingBookings->count() }}</span>
                    </button>
                    <button class="tab-btn" onclick="filterByStatus('confirmed')">
                        <i class="fa-solid fa-circle-check"></i> Đã xác nhận
                        <span class="tab-count">{{ $confirmedBookings->count() }}</span>
                    </button>
                    <button class="tab-btn" onclick="filterByStatus('completed')">
                        <i class="fa-solid fa-flag-checkered"></i> Hoàn thành
                        <span class="tab-count">{{ $completedBookings->count() }}</span>
                    </button>
                    <button class="tab-btn" onclick="filterByStatus('cancelled')">
                        <i class="fa-solid fa-circle-xmark"></i> Hủy/Hết hạn
                        <span class="tab-count">{{ $cancelledBookings->count() }}</span>
                    </button>
                </div>
                
                {{-- SEARCH --}}
                <div class="search-box-container">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" id="bookingSearch" class="search-input" placeholder="Tìm mã đơn #33, #25..." onkeyup="searchBookings()">
                </div>
            </div>

            <section class="booking-section" id="section-unpaid">
                <h2 class="booking-section-title">Chờ thanh toán ({{ ($unpaidBookings ?? collect())->count() }})</h2>
                @if (($unpaidBookings ?? collect())->isEmpty())
                <div class="empty-state">Hiện không có booking nào đang chờ thanh toán.</div>
                @else
                <div class="booking-list">
                    @foreach ($unpaidBookings as $booking)
                    <article class="booking-item">
                        <div class="booking-item-head">
                            <h3 class="booking-title">{{ $booking->tour->name ?? 'Tour không xác định' }}</h3>
                            <div class="d-flex align-items-center gap-3">
                                @if($booking->expires_at && $booking->expires_at->isFuture())
                                <div class="payment-countdown"
                                    data-expires="{{ $booking->expires_at->toISOString() }}"
                                    id="timer-{{ $booking->booking_id }}">
                                    <i class="fa-solid fa-clock"></i>
                                    <span class="timer-display">--:--</span>
                                </div>
                                @endif
                                <span class="status-unpaid">Chờ thanh toán</span>
                            </div>
                        </div>

                        <div class="booking-meta">
                            <div>Mã booking: #{{ $booking->booking_id }}</div>
                            <div>Ngày đặt: {{ optional($booking->booking_date)->format('d/m/Y H:i') }}</div>
                            <div>Khởi hành: {{ optional(optional($booking->schedule)->start_date)->format('d/m/Y') ?? '-' }}</div>
                            <div>Số người: {{ $booking->num_people }} người</div>
                        </div>

                        <div class="booking-price-row">
                            <div class="booking-price">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</div>
                            <div class="booking-actions">
                                @if ($booking->tour_id)
                                <a class="btn-action btn-view" href="{{ route('tours.show', $booking->tour_id) }}">
                                    <i class="fa-solid fa-eye"></i> Xem tour
                                </a>
                                @endif

                                <a class="btn-action btn-pay" href="{{ route('payment.choose', $booking->booking_id) }}">
                                    <i class="fa-solid fa-credit-card"></i> Thanh toán ngay
                                </a>

                                @if ($booking->canBeCancelledByUser())
                                <form class="booking-cancel-form" action="{{ route('user.booking.cancel', $booking->booking_id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy booking này không?')">
                                    @csrf
                                    <button type="submit" class="booking-cancel-btn">
                                        <i class="fa-solid fa-trash"></i> Hủy đơn
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif
            </section>

            <section class="booking-section" id="section-pending">
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

                        <div class="booking-price-row">
                            <div class="booking-price">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</div>
                            <div class="booking-actions">
                                @if ($booking->tour_id)
                                <a class="btn-action btn-view" href="{{ route('tours.show', $booking->tour_id) }}">
                                    <i class="fa-solid fa-eye"></i> Xem tour
                                </a>
                                @endif
                                @if ($booking->canBeCancelledByUser())
                                <form class="booking-cancel-form" action="{{ route('user.booking.cancel', $booking->booking_id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy booking này không?')">
                                    @csrf
                                    <button type="submit" class="booking-cancel-btn">
                                        <i class="fa-solid fa-trash"></i> Hủy đơn
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif
            </section>

            <section class="booking-section" id="section-confirmed">
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

                        <div class="booking-price-row">
                            <div class="booking-price">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</div>
                            <div class="booking-actions">
                                @if ($booking->tour_id)
                                <a class="btn-action btn-view" href="{{ route('tours.show', $booking->tour_id) }}">
                                    <i class="fa-solid fa-eye"></i> Xem tour
                                </a>
                                @endif
                                @if ($booking->canBeCancelledByUser())
                                <form class="booking-cancel-form" action="{{ route('user.booking.cancel', $booking->booking_id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy booking này không?')">
                                    @csrf
                                    <button type="submit" class="booking-cancel-btn">
                                        <i class="fa-solid fa-trash"></i> Hủy đơn
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif
            </section>

            <section class="booking-section" id="section-completed">
                <h2 class="booking-section-title">Đã hoàn thành ({{ ($completedBookings ?? collect())->count() }})</h2>
                @if (($completedBookings ?? collect())->isEmpty())
                <div class="empty-state">Chưa có tour nào hoàn thành.</div>
                @else
                <div class="booking-list">
                    @foreach ($completedBookings as $booking)
                    @php $feedback = $booking->feedbacks->first(); @endphp
                    <article class="booking-item">
                        <div class="booking-item-head">
                            <h3 class="booking-title">{{ $booking->tour->name ?? 'Tour không xác định' }}</h3>
                            <span class="status-completed">Hoàn thành</span>
                        </div>

                        <div class="booking-meta">
                            <div>Mã booking: #{{ $booking->booking_id }}</div>
                            <div>Ngày đặt: {{ optional($booking->booking_date)->format('d/m/Y H:i') }}</div>
                            <div>Khởi hành: {{ optional(optional($booking->schedule)->start_date)->format('d/m/Y') ?? '-' }}</div>
                            <div>Kết thúc: {{ optional(optional($booking->schedule)->end_date)->format('d/m/Y') ?? '-' }}</div>
                        </div>

                        <div class="booking-price-row">
                            <div class="booking-price">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</div>
                            <div class="booking-actions">
                                @if ($booking->tour_id)
                                <a class="btn-action btn-view" href="{{ route('tours.show', $booking->tour_id) }}">
                                    <i class="fa-solid fa-eye"></i> Xem tour
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="feedback-card">
                            @if ($feedback)
                            <h4 class="feedback-title">Đánh giá của bạn</h4>
                            <div class="feedback-meta-box">
                                <div class="feedback-stars">{{ str_repeat('★', (int) $feedback->rating) }}{{ str_repeat('☆', max(0, 5 - (int) $feedback->rating)) }}</div>
                                <div>{{ $feedback->content }}</div>
                                <div>Gửi lúc: {{ optional($feedback->created_at)->format('d/m/Y H:i') }}</div>
                            </div>
                            @else
                            <h4 class="feedback-title">Đánh giá tour đã hoàn thành</h4>
                            <form method="POST" action="{{ route('user.booking.feedback', $booking->booking_id) }}" class="feedback-form">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">

                                <div class="feedback-field">
                                    <label for="rating-{{ $booking->booking_id }}">Số sao</label>
                                    <select id="rating-{{ $booking->booking_id }}" name="rating" class="feedback-input">
                                        <option value="">Chọn số sao</option>
                                        @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" @selected(old('booking_id') == $booking->booking_id && (int) old('rating') === $i)>{{ $i }} sao</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="feedback-field">
                                    <label for="content-{{ $booking->booking_id }}">Nội dung đánh giá</label>
                                    <textarea id="content-{{ $booking->booking_id }}" name="content" class="feedback-textarea" placeholder="Chia sẻ trải nghiệm của bạn về tour này...">{{ old('booking_id') == $booking->booking_id ? old('content') : '' }}</textarea>
                                </div>

                                <div>
                                    <button type="submit" class="btn-action btn-pay">
                                        <i class="fa-solid fa-paper-plane"></i> Gửi đánh giá
                                    </button>
                                </div>
                            </form>
                            @endif
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif
            </section>

            <section class="booking-section" id="section-cancelled">
                <h2 class="booking-section-title">Đã hủy ({{ ($cancelledBookings ?? collect())->count() }})</h2>
                @if (($cancelledBookings ?? collect())->isEmpty())
                <div class="empty-state">Bạn chưa hủy booking nào.</div>
                @else
                <div class="booking-list">
                    @foreach ($cancelledBookings as $booking)
                    <article class="booking-item">
                        <div class="booking-item-head">
                            <h3 class="booking-title">{{ $booking->tour->name ?? 'Tour không xác định' }}</h3>
                            @if(str_contains($booking->note ?? '', 'quá hạn thanh toán'))
                            <span class="status-expired">Hết hạn</span>
                            @else
                            <span class="status-cancelled">Đã hủy</span>
                            @endif
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

                        <div class="booking-price-row">
                            <div class="booking-price">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</div>
                            <div class="booking-actions">
                                @if ($booking->payment_status === 'refunded')
                                <span class="status-refunded">Đã hoàn tiền</span>
                                @endif
                                @if ($booking->tour_id)
                                <a class="btn-action btn-view" href="{{ route('tours.show', $booking->tour_id) }}">
                                    <i class="fa-solid fa-eye"></i> Xem tour
                                </a>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timers = document.querySelectorAll('.payment-countdown');

        function updateTimers() {
            const now = new Date();
            let activeTimers = 0;

            timers.forEach(timer => {
                const expiryDate = new Date(timer.dataset.expires);
                const diff = expiryDate - now;

                if (diff <= 0) {
                    timer.innerHTML = '<i class="fa-solid fa-clock"></i> Hết hạn';
                    timer.style.color = '#718096';
                    timer.style.background = '#edf2f7';
                    timer.style.borderColor = '#cbd5e0';
                    return;
                }

                activeTimers++;
                const minutes = Math.floor(diff / 1000 / 60);
                const seconds = Math.floor((diff / 1000) % 60);

                const display = timer.querySelector('.timer-display');
                if (display) {
                    display.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                }
            });

            if (activeTimers > 0) {
                setTimeout(updateTimers, 1000);
            }
        }

        if (timers.length > 0) {
            updateTimers();
        }

        // TABS LOGIC
        let currentStatus = 'all';

        window.filterByStatus = function(status) {
            currentStatus = status;
            // Update Tab Buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');

            // Clear search when switching tabs to avoid confusion
            document.getElementById('bookingSearch').value = '';
            
            applyFilters();
        };

        // SEARCH LOGIC
        window.searchBookings = function() {
            applyFilters();
        };

        function applyFilters() {
            const query = document.getElementById('bookingSearch').value.trim().toLowerCase().replace('#', '');
            const items = document.querySelectorAll('.booking-item');
            const sections = {
                'unpaid': document.getElementById('section-unpaid'),
                'pending': document.getElementById('section-pending'),
                'confirmed': document.getElementById('section-confirmed'),
                'completed': document.getElementById('section-completed'),
                'cancelled': document.getElementById('section-cancelled')
            };

            // 1. Filter items by search query
            items.forEach(item => {
                const bookingId = item.querySelector('.booking-meta div:nth-child(1)').textContent.replace('Mã booking: #', '').trim();
                const tourName = item.querySelector('.booking-title').textContent.toLowerCase();
                
                if (query === '' || bookingId.includes(query) || tourName.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });

            // 2. Filter sections by status tab AND matching items
            Object.keys(sections).forEach(key => {
                const section = sections[key];
                if (!section) return;

                const hasMatchingItems = section.querySelectorAll('.booking-item[style="display: block;"]').length > 0;
                const matchesStatus = (currentStatus === 'all' || currentStatus === key);

                if (matchesStatus && hasMatchingItems) {
                    section.classList.remove('hidden');
                } else {
                    section.classList.add('hidden');
                }
            });
        }
    });
</script>
@endsection