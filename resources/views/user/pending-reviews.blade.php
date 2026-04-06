@extends('layouts.user')

@section('title', 'Chờ đánh giá - GoTour')

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
        --warning: #f59e0b;
    }

    .review-page {
        background: var(--bg);
        min-height: calc(100vh - 80px);
        padding: 40px 0 80px;
    }

    .container { max-width: 860px; margin: 0 auto; padding: 0 20px; }

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
    .page-sub   { font-size: 14px; color: var(--text-gray); margin-top: 4px; }

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

    /* Review card */
    .review-card {
        background: var(--white);
        border-radius: 16px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .review-card-head {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 16px;
        background: #fffbeb;
    }

    .tour-thumb {
        width: 56px; height: 56px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
        background: #dbeafe;
    }

    .tour-thumb-placeholder {
        width: 56px; height: 56px;
        border-radius: 12px;
        background: linear-gradient(135deg, #3b82f6, #0066cc);
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .tour-info h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 4px;
    }

    .tour-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 12px;
        color: var(--text-gray);
    }

    .tour-meta span { display: flex; align-items: center; gap: 4px; }

    .badge-pending {
        margin-left: auto;
        background: #fef3c7;
        color: #92400e;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .review-card-body { padding: 20px 24px 24px; }

    /* Star rating */
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        gap: 4px;
        justify-content: flex-end;
        margin-bottom: 4px;
    }

    .star-rating input { display: none; }

    .star-rating label {
        font-size: 34px;
        color: #d1d5db;
        cursor: pointer;
        transition: color .15s;
        line-height: 1;
    }

    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: var(--warning);
    }

    .form-group { margin-bottom: 16px; }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .form-group textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        min-height: 100px;
        transition: border-color .2s;
    }

    .form-group textarea:focus {
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
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit:hover { background: var(--primary-dark); transform: translateY(-1px); }

    .btn-skip {
        padding: 12px 20px;
        background: #f1f5f9;
        color: var(--text-gray);
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background .2s;
    }
    .btn-skip:hover { background: #e2e8f0; }

    .form-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

    /* Empty */
    .empty-state {
        text-align: center;
        padding: 60px 24px;
        background: var(--white);
        border-radius: 16px;
        border: 1px solid var(--border);
    }
    .empty-icon { font-size: 56px; margin-bottom: 14px; }
    .empty-title { font-size: 18px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; }
    .empty-sub { font-size: 14px; color: var(--text-gray); }

    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--warning);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        width: 26px; height: 26px;
        border-radius: 50%;
        margin-left: 8px;
    }

    @media (max-width: 600px) {
        .review-card-head { flex-wrap: wrap; }
        .badge-pending { margin-left: 0; }
    }
</style>
@endsection

@section('content')
<div class="review-page">
    <div class="container">

        <div class="page-head">
            <a href="{{ route('user.profile') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Hồ sơ
            </a>
            <div>
                <h1 class="page-title">
                    Chờ đánh giá
                    @if($pendingReviews->count())
                        <span class="count-badge">{{ $pendingReviews->count() }}</span>
                    @endif
                </h1>
                <div class="page-sub">Các tour đã hoàn thành và chưa được đánh giá</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @if($pendingReviews->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">🎉</div>
                <div class="empty-title">Bạn đã đánh giá tất cả các tour!</div>
                <div class="empty-sub">Không có tour nào đang chờ đánh giá. Cảm ơn bạn đã chia sẻ trải nghiệm.</div>
            </div>
        @else
            @foreach($pendingReviews as $booking)
            <div class="review-card">
                <div class="review-card-head">
                    @if($booking->tour?->image_url)
                        <img src="{{ $booking->tour->image_url }}" alt="{{ $booking->tour->name }}" class="tour-thumb">
                    @else
                        <div class="tour-thumb-placeholder">🗺️</div>
                    @endif
                    <div class="tour-info">
                        <h3>{{ $booking->tour->name ?? 'Tour không xác định' }}</h3>
                        <div class="tour-meta">
                            <span><i class="fas fa-hashtag"></i> Booking #{{ $booking->booking_id }}</span>
                            @if($booking->schedule?->start_date)
                                <span><i class="fas fa-calendar"></i> {{ $booking->schedule->start_date->format('d/m/Y') }}</span>
                            @endif
                            @if($booking->schedule?->end_date)
                                <span><i class="fas fa-flag-checkered"></i> {{ $booking->schedule->end_date->format('d/m/Y') }}</span>
                            @endif
                            <span><i class="fas fa-users"></i> {{ $booking->num_people }} người</span>
                        </div>
                    </div>
                    <span class="badge-pending">⏳ Chờ đánh giá</span>
                </div>

                <div class="review-card-body">
                    <form method="POST" action="{{ route('user.booking.feedback', $booking->booking_id) }}">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">

                        <div class="form-group">
                            <label>Số sao đánh giá</label>
                            <div class="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star-{{ $booking->booking_id }}-{{ $i }}"
                                        name="rating" value="{{ $i }}"
                                        {{ old('booking_id') == $booking->booking_id && old('rating') == $i ? 'checked' : '' }}>
                                    <label for="star-{{ $booking->booking_id }}-{{ $i }}" title="{{ $i }} sao">★</label>
                                @endfor
                            </div>
                            @error('rating')
                                <div style="color:#dc2626; font-size:13px; margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content-{{ $booking->booking_id }}">Nội dung đánh giá</label>
                            <textarea id="content-{{ $booking->booking_id }}" name="content"
                                placeholder="Chia sẻ trải nghiệm của bạn về tour này...">{{ old('booking_id') == $booking->booking_id ? old('content') : '' }}</textarea>
                            @error('content')
                                <div style="color:#dc2626; font-size:13px; margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-paper-plane"></i> Gửi đánh giá
                            </button>
                            <a href="{{ route('user.booking.detail', $booking->booking_id) }}" class="btn-skip">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        @endif

    </div>
</div>
@endsection
