@extends('layouts.user')

@section('title', 'Chi tiết đặt tour - GoTour')

@section('styles')
<style>
    :root {
        --primary: #0066cc;
        --primary-dark: #004fa3;
        --text-dark: #1e293b;
        --text-gray: #64748b;
        --bg: #f8fafc;
        --white: #fff;
        --border: #e2e8f0;
        --radius: 16px;
    }

    .detail-page {
        background: var(--bg);
        min-height: calc(100vh - 80px);
        padding: 40px 0 80px;
    }

    .container { max-width: 900px; margin: 0 auto; padding: 0 20px; }

    .page-head {
        margin-top: 50px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 28px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        background: var(--white);
        color: var(--primary);
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        border: 1px solid var(--border);
        transition: background .2s;
    }
    .back-btn:hover { background: #e8f2ff; }

    .page-title { font-size: 24px; font-weight: 800; color: var(--text-dark); }

    .card {
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: 0 4px 20px rgba(0,0,0,.06);
        border: 1px solid var(--border);
        margin-bottom: 20px;
    }

    .card-header {
        padding: 20px 24px 16px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: #e8f2ff;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .card-header h2 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .card-body { padding: 20px 24px 24px; }

    /* Tour hero */
    .tour-hero {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .tour-img {
        width: 140px;
        height: 100px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
        background: #dbeafe;
    }

    .tour-img-placeholder {
        width: 140px;
        height: 100px;
        border-radius: 12px;
        background: linear-gradient(135deg, #3b82f6, #0066cc);
        display: flex; align-items: center; justify-content: center;
        font-size: 36px;
        flex-shrink: 0;
    }

    .tour-info h3 {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-dark);
        margin: 0 0 8px;
    }

    .tour-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 13px;
        color: var(--text-gray);
    }

    .tour-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Info grid */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .info-item label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-gray);
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 4px;
    }

    .info-item .value {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-dark);
    }

    /* Status badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .badge-completed { background: #ede9fe; color: #6d28d9; }
    .badge-upcoming  { background: #dbeafe; color: #1e40af; }
    .badge-ongoing   { background: #d1fae5; color: #065f46; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }
    .badge-paid      { background: #d1fae5; color: #065f46; }
    .badge-unpaid    { background: #fef3c7; color: #92400e; }

    /* Price highlight */
    .price-highlight {
        font-size: 22px;
        font-weight: 800;
        color: var(--primary);
    }

    /* Divider */
    .divider { border: none; border-top: 1px solid var(--border); margin: 16px 0; }

    /* Star rating input */
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        gap: 4px;
        justify-content: flex-end;
    }

    .star-rating input { display: none; }

    .star-rating label {
        font-size: 32px;
        color: #d1d5db;
        cursor: pointer;
        transition: color .15s;
        line-height: 1;
    }

    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #f59e0b;
    }

    /* Feedback form */
    .feedback-form .form-group { margin-bottom: 16px; }

    .feedback-form label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .feedback-form textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        min-height: 110px;
        transition: border-color .2s;
    }

    .feedback-form textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(0,102,204,.1);
    }

    .btn-submit {
        padding: 12px 28px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s, transform .1s;
    }
    .btn-submit:hover { background: var(--primary-dark); transform: translateY(-1px); }

    /* Existing review display */
    .review-display {
        background: #f8fafc;
        border-radius: 12px;
        padding: 18px 20px;
        border: 1px solid var(--border);
    }

    .review-stars { font-size: 22px; color: #f59e0b; letter-spacing: 2px; margin-bottom: 8px; }

    .review-content { font-size: 15px; color: var(--text-dark); line-height: 1.6; margin-bottom: 8px; }

    .review-date { font-size: 12px; color: var(--text-gray); }

    /* Alert */
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
    .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    /* Itinerary */
    .itinerary-list { display: flex; flex-direction: column; gap: 12px; }

    .itinerary-item {
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .itinerary-day {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .itinerary-content h4 { font-size: 14px; font-weight: 700; color: var(--text-dark); margin: 0 0 4px; }
    .itinerary-content p  { font-size: 13px; color: var(--text-gray); margin: 0; line-height: 1.5; }

    @media (max-width: 640px) {
        .tour-hero { flex-direction: column; }
        .tour-img, .tour-img-placeholder { width: 100%; height: 160px; }
        .info-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="detail-page">
    <div class="container">

        <div class="page-head">
            <a href="{{ route('user.bookings') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Lịch sử đặt tour
            </a>
            <h1 class="page-title">Chi tiết đặt tour</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        {{-- Tour Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon">✈️</div>
                <h2>Thông tin tour</h2>
            </div>
            <div class="card-body">
                <div class="tour-hero">
                    @if($booking->tour?->image_url)
                        <img src="{{ $booking->tour->image_url }}" alt="{{ $booking->tour->name }}" class="tour-img">
                    @else
                        <div class="tour-img-placeholder">🗺️</div>
                    @endif
                    <div class="tour-info">
                        <h3>{{ $booking->tour->name ?? 'Tour không xác định' }}</h3>
                        <div class="tour-meta">
                            @if($booking->tour?->category)
                                <span><i class="fas fa-tag"></i> {{ $booking->tour->category->name }}</span>
                            @endif
                            @if($booking->tour?->duration)
                                <span><i class="fas fa-clock"></i> {{ $booking->tour->duration }}</span>
                            @endif
                            @if($booking->schedule?->meeting_point)
                                <span><i class="fas fa-map-marker-alt"></i> {{ $booking->schedule->meeting_point }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Booking Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon">📋</div>
                <h2>Thông tin đặt chỗ</h2>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Mã đặt tour</label>
                        <div class="value">#{{ $booking->booking_id }}</div>
                    </div>
                    <div class="info-item">
                        <label>Ngày đặt</label>
                        <div class="value">{{ $booking->booking_date?->format('d/m/Y H:i') ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <label>Ngày khởi hành</label>
                        <div class="value">{{ $booking->schedule?->start_date?->format('d/m/Y') ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <label>Ngày kết thúc</label>
                        <div class="value">{{ $booking->schedule?->end_date?->format('d/m/Y') ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <label>Số người</label>
                        <div class="value">{{ $booking->num_people }} người</div>
                    </div>
                    <div class="info-item">
                        <label>Trạng thái tour</label>
                        <div class="value">
                            @php
                                $statusMap = ['completed'=>'badge-completed','upcoming'=>'badge-upcoming','ongoing'=>'badge-ongoing','cancelled'=>'badge-cancelled'];
                                $statusLabel = \App\Models\Booking::STATUS[$booking->status] ?? $booking->status;
                                $badgeClass = $statusMap[$booking->status] ?? 'badge-upcoming';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <label>Thanh toán</label>
                        <div class="value">
                            <span class="badge {{ $booking->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                                {{ \App\Models\Booking::PAYMENT_STATUS[$booking->payment_status] ?? $booking->payment_status }}
                            </span>
                        </div>
                    </div>
                    @if($booking->payment_method)
                    <div class="info-item">
                        <label>Phương thức thanh toán</label>
                        <div class="value">{{ strtoupper($booking->payment_method) }}</div>
                    </div>
                    @endif
                </div>

                <hr class="divider">

                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:15px; color:var(--text-gray); font-weight:600;">Tổng tiền</span>
                    <span class="price-highlight">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</span>
                </div>

                @if($booking->note)
                    <hr class="divider">
                    <div class="info-item">
                        <label>Ghi chú</label>
                        <div class="value" style="font-weight:400; color:var(--text-gray);">{{ $booking->note }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon">👤</div>
                <h2>Thông tin khách hàng</h2>
            </div>
            <div class="card-body">
                @if($booking->customer)
                <div class="info-grid">
                    <div class="info-item">
                        <label>Họ và tên</label>
                        <div class="value">{{ $booking->customer->fullname }}</div>
                    </div>
                    <div class="info-item">
                        <label>Số điện thoại</label>
                        <div class="value">{{ $booking->customer->phone ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <label>Email</label>
                        <div class="value">{{ $booking->customer->email ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <label>Giới tính</label>
                        <div class="value">
                            @php
                                $genderMap = ['male' => 'Nam', 'female' => 'Nữ', 'other' => 'Khác'];
                            @endphp
                            {{ $genderMap[$booking->customer->gender] ?? '—' }}
                        </div>
                    </div>
                    @if($booking->customer->id_number)
                    <div class="info-item">
                        <label>CCCD / Hộ chiếu</label>
                        <div class="value">{{ $booking->customer->id_number }}</div>
                    </div>
                    @endif
                    @if($booking->customer->birthdate)
                    <div class="info-item">
                        <label>Ngày sinh</label>
                        <div class="value">{{ \Carbon\Carbon::parse($booking->customer->birthdate)->format('d/m/Y') }}</div>
                    </div>
                    @endif
                </div>
                @else
                <p style="color:var(--text-gray); font-size:14px;">Không có thông tin khách hàng.</p>
                @endif
            </div>
        </div>

        {{-- Invoice --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon">🧾</div>
                <h2>Hóa đơn</h2>
            </div>
            <div class="card-body">
                @php
                    $unitPrice = $booking->num_people > 0
                        ? round($booking->total_price / $booking->num_people)
                        : $booking->tour?->price ?? 0;
                @endphp

                {{-- Invoice table --}}
                <table style="width:100%; border-collapse:collapse; font-size:14px;">
                    <thead>
                        <tr style="background:#f8fafc; border-bottom:2px solid var(--border);">
                            <th style="text-align:left; padding:10px 12px; color:var(--text-gray); font-weight:700; text-transform:uppercase; font-size:12px; letter-spacing:.5px;">Dịch vụ</th>
                            <th style="text-align:center; padding:10px 12px; color:var(--text-gray); font-weight:700; text-transform:uppercase; font-size:12px; letter-spacing:.5px;">Số lượng</th>
                            <th style="text-align:right; padding:10px 12px; color:var(--text-gray); font-weight:700; text-transform:uppercase; font-size:12px; letter-spacing:.5px;">Đơn giá</th>
                            <th style="text-align:right; padding:10px 12px; color:var(--text-gray); font-weight:700; text-transform:uppercase; font-size:12px; letter-spacing:.5px;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom:1px solid var(--border);">
                            <td style="padding:14px 12px;">
                                <div style="font-weight:600; color:var(--text-dark);">{{ $booking->tour->name ?? 'Tour' }}</div>
                                @if($booking->schedule)
                                <div style="font-size:12px; color:var(--text-gray); margin-top:3px;">
                                    {{ $booking->schedule->start_date?->format('d/m/Y') }} — {{ $booking->schedule->end_date?->format('d/m/Y') }}
                                </div>
                                @endif
                            </td>
                            <td style="text-align:center; padding:14px 12px; font-weight:600;">{{ $booking->num_people }} người</td>
                            <td style="text-align:right; padding:14px 12px; color:var(--text-gray);">{{ number_format($unitPrice, 0, ',', '.') }} ₫</td>
                            <td style="text-align:right; padding:14px 12px; font-weight:700; color:var(--text-dark);">{{ number_format($booking->num_people * $unitPrice, 0, ',', '.') }} ₫</td>
                        </tr>
                    </tbody>
                </table>

                <hr class="divider">

                {{-- Summary --}}
                <div style="display:flex; flex-direction:column; gap:10px; max-width:340px; margin-left:auto;">
                    <div style="display:flex; justify-content:space-between; font-size:14px; color:var(--text-gray);">
                        <span>Tạm tính ({{ $booking->num_people }} người)</span>
                        <span>{{ number_format($booking->total_price, 0, ',', '.') }} ₫</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:14px; color:var(--text-gray);">
                        <span>Phí dịch vụ</span>
                        <span>Miễn phí</span>
                    </div>
                    <hr class="divider" style="margin:4px 0;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:15px; font-weight:700; color:var(--text-dark);">Tổng cộng</span>
                        <span class="price-highlight">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:13px; color:var(--text-gray);">
                        <span>Trạng thái thanh toán</span>
                        <span class="badge {{ $booking->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                            {{ \App\Models\Booking::PAYMENT_STATUS[$booking->payment_status] ?? $booking->payment_status }}
                        </span>
                    </div>
                    @if($booking->payment_method)
                    <div style="display:flex; justify-content:space-between; font-size:13px; color:var(--text-gray);">
                        <span>Phương thức</span>
                        <span style="font-weight:600; color:var(--text-dark);">{{ strtoupper($booking->payment_method) }}</span>
                    </div>
                    @endif
                    @if($booking->vnp_transaction_no)
                    <div style="display:flex; justify-content:space-between; font-size:13px; color:var(--text-gray);">
                        <span>Mã giao dịch</span>
                        <span style="font-weight:600; color:var(--text-dark); font-family:monospace;">{{ $booking->vnp_transaction_no }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Itinerary --}}
        @if($booking->tour?->itineraries?->count())
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon">🗓️</div>
                <h2>Lịch trình tour</h2>
            </div>
            <div class="card-body">
                <div class="itinerary-list">
                    @foreach($booking->tour->itineraries->sortBy('day_number') as $item)
                    <div class="itinerary-item">
                        <div class="itinerary-day">N{{ $item->day_number }}</div>
                        <div class="itinerary-content">
                            <h4>{{ $item->title }}</h4>
                            @if($item->description)
                                <p>{{ $item->description }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Feedback / Review --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon">⭐</div>
                <h2>Đánh giá tour</h2>
            </div>
            <div class="card-body">
                @if($feedback)
                    {{-- Đã đánh giá --}}
                    <div class="review-display">
                        <div class="review-stars">
                            {{ str_repeat('★', (int) $feedback->rating) }}{{ str_repeat('☆', max(0, 5 - (int) $feedback->rating)) }}
                            <span style="font-size:14px; color:var(--text-gray); font-weight:600; margin-left:6px;">{{ $feedback->rating }}/5</span>
                        </div>
                        <div class="review-content">{{ $feedback->content }}</div>
                        <div class="review-date"><i class="fas fa-clock"></i> Gửi lúc {{ optional($feedback->created_at)->format('d/m/Y H:i') }}</div>
                    </div>

                @elseif($booking->canBeReviewed())
                    {{-- Form đánh giá --}}
                    <p style="font-size:14px; color:var(--text-gray); margin-bottom:20px;">
                        Tour đã hoàn thành. Hãy chia sẻ trải nghiệm của bạn để giúp những khách hàng khác nhé!
                    </p>

                    <form method="POST" action="{{ route('user.booking.feedback', $booking->booking_id) }}" class="feedback-form">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">

                        <div class="form-group">
                            <label>Số sao đánh giá</label>
                            <div class="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                        {{ old('rating') == $i ? 'checked' : '' }}>
                                    <label for="star{{ $i }}" title="{{ $i }} sao">★</label>
                                @endfor
                            </div>
                            @error('rating')
                                <div style="color:#dc2626; font-size:13px; margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">Nội dung đánh giá</label>
                            <textarea id="content" name="content" placeholder="Chia sẻ trải nghiệm của bạn về tour này...">{{ old('content') }}</textarea>
                            @error('content')
                                <div style="color:#dc2626; font-size:13px; margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i> Gửi đánh giá
                        </button>
                    </form>

                @else
                    <div style="text-align:center; padding:24px; color:var(--text-gray);">
                        <div style="font-size:40px; margin-bottom:10px;">🔒</div>
                        <div style="font-size:14px;">Chỉ có thể đánh giá sau khi tour hoàn thành.</div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
