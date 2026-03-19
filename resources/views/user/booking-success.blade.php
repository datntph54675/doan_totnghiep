@extends('user.layout')

@section('title', 'Đặt tour thành công')

@push('styles')
<style>
    .success-wrap { max-width: 640px; margin: 60px auto; padding: 0 20px; text-align: center; }
    .success-icon { font-size: 72px; margin-bottom: 16px; }
    .success-title { font-size: 28px; font-weight: 800; color: #1e293b; margin-bottom: 8px; }
    .success-sub { font-size: 15px; color: #64748b; margin-bottom: 32px; }

    .booking-card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 28px; text-align: left; margin-bottom: 24px; }
    .booking-card h3 { font-size: 16px; font-weight: 700; margin-bottom: 18px; color: #1e293b; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; }
    .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f8fafc; font-size: 14px; }
    .info-row:last-child { border-bottom: none; }
    .info-row .label { color: #64748b; }
    .info-row .value { font-weight: 600; color: #1e293b; }
    .info-row .value.price { color: #0ea5e9; font-size: 18px; }

    .badge-unpaid { background: #fef3c7; color: #d97706; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-upcoming { background: #dbeafe; color: #1d4ed8; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }

    .btn-home {
        display: inline-block; padding: 12px 32px;
        background: linear-gradient(135deg, #0ea5e9, #6366f1);
        color: #fff; border-radius: 10px; text-decoration: none;
        font-size: 15px; font-weight: 700;
        transition: opacity .2s;
    }
    .btn-home:hover { opacity: .9; }
    .btn-outline {
        display: inline-block; padding: 12px 32px;
        border: 2px solid #e2e8f0; color: #475569;
        border-radius: 10px; text-decoration: none;
        font-size: 15px; font-weight: 600; margin-right: 12px;
        transition: border-color .2s;
    }
    .btn-outline:hover { border-color: #0ea5e9; color: #0ea5e9; }

    .notice { background: #fefce8; border: 1px solid #fde68a; border-radius: 10px; padding: 14px 18px; font-size: 13px; color: #92400e; margin-bottom: 24px; text-align: left; }
</style>
@endpush

@section('content')
<div class="success-wrap">
    <div class="success-icon">🎉</div>
    <div class="success-title">Đặt tour thành công!</div>
    <div class="success-sub">Cảm ơn bạn đã đặt tour. Chúng tôi sẽ liên hệ xác nhận sớm nhất.</div>

    <div class="booking-card">
        <h3>📋 Thông tin đặt tour #{{ $booking->booking_id }}</h3>
        <div class="info-row">
            <span class="label">Tour</span>
            <span class="value">{{ $booking->tour->name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Ngày khởi hành</span>
            <span class="value">{{ $booking->schedule->start_date->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Ngày kết thúc</span>
            <span class="value">{{ $booking->schedule->end_date->format('d/m/Y') }}</span>
        </div>
        @if($booking->schedule->meeting_point)
        <div class="info-row">
            <span class="label">Điểm tập trung</span>
            <span class="value">{{ $booking->schedule->meeting_point }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="label">Số người</span>
            <span class="value">{{ $booking->num_people }} người</span>
        </div>
        <div class="info-row">
            <span class="label">Tổng tiền</span>
            <span class="value price">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</span>
        </div>
        <div class="info-row">
            <span class="label">Trạng thái</span>
            <span class="value"><span class="badge-upcoming">Sắp tới</span></span>
        </div>
        <div class="info-row">
            <span class="label">Thanh toán</span>
            <span class="value"><span class="badge-unpaid">Chưa thanh toán</span></span>
        </div>
    </div>

    <div class="booking-card">
        <h3>👤 Thông tin khách hàng</h3>
        <div class="info-row">
            <span class="label">Họ tên</span>
            <span class="value">{{ $booking->customer->fullname }}</span>
        </div>
        <div class="info-row">
            <span class="label">Điện thoại</span>
            <span class="value">{{ $booking->customer->phone }}</span>
        </div>
        @if($booking->customer->email)
        <div class="info-row">
            <span class="label">Email</span>
            <span class="value">{{ $booking->customer->email }}</span>
        </div>
        @endif
        @if($booking->note)
        <div class="info-row">
            <span class="label">Ghi chú</span>
            <span class="value">{{ $booking->note }}</span>
        </div>
        @endif
    </div>

    <div class="notice">
        📞 Nhân viên sẽ liên hệ qua số <strong>{{ $booking->customer->phone }}</strong> để xác nhận và hướng dẫn thanh toán.
    </div>

    <a href="{{ route('user.tours') }}" class="btn-outline">← Xem thêm tour</a>
    <a href="{{ route('user.tour.detail', $booking->tour_id) }}" class="btn-home">Xem lại tour</a>
</div>
@endsection
