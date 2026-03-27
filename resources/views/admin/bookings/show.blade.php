@extends('layouts.app')

@section('title', 'Chi tiết Đặt tour')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}" class="text-decoration-none">Đặt tour</a></li>
            <li class="breadcrumb-item active">Chi tiết</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-dark">Chi tiết Đặt tour</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
            @if(!$booking->admin_confirmed)
            <form action="{{ route('admin.bookings.confirm', $booking->booking_id) }}" method="POST" onsubmit="return confirm('Xác nhận đơn này?')">
                @csrf
                <button type="submit" class="btn btn-success px-4 shadow-sm">
                    <i class="fas fa-check me-1"></i> Xác nhận
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>Thông tin booking
                </h5>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <tbody>
                            <tr class="border-bottom">
                                <th class="py-3 text-muted fw-semibold text-uppercase small" style="width: 220px;">Booking ID</th>
                                <td class="py-3 fw-bold text-dark">#{{ $booking->booking_id }}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="py-3 text-muted fw-semibold text-uppercase small">Tour</th>
                                <td class="py-3 fw-bold text-primary">{{ $booking->tour->name ?? '—' }}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="py-3 text-muted fw-semibold text-uppercase small">Khách hàng</th>
                                <td class="py-3">{{ $booking->customer->fullname ?? '—' }} ({{ $booking->customer->phone ?? '—' }})</td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="py-3 text-muted fw-semibold text-uppercase small">Ngày khởi hành</th>
                                <td class="py-3">{{ $booking->schedule && $booking->schedule->start_date ? $booking->schedule->start_date->format('d/m/Y') : '—' }}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="py-3 text-muted fw-semibold text-uppercase small">Số người</th>
                                <td class="py-3">{{ $booking->num_people }}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="py-3 text-muted fw-semibold text-uppercase small">Tổng tiền</th>
                                <td class="py-3 fw-bold text-success">{{ number_format($booking->total_price, 0, ',', '.') }} VND</td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="py-3 text-muted fw-semibold text-uppercase small">Ngày đặt</th>
                                <td class="py-3">{{ $booking->booking_date ? $booking->booking_date->format('d/m/Y H:i') : '—' }}</td>
                            </tr>
                            <tr>
                                <th class="py-3 text-muted fw-semibold text-uppercase small">Trạng thái xác nhận</th>
                                <td class="py-3">
                                    @if($booking->admin_confirmed)
                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i> Đã xác nhận
                                    </span>
                                    @else
                                    <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                        <i class="fas fa-clock me-1"></i> Chờ xác nhận
                                    </span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection