@extends('layouts.app')

@section('title', 'Quản lý Booking')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Quản lý Booking</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Booking</li>
            </ol>
        </nav>
    </div>
</div>

<div class="mb-3">
    <ul class="nav nav-pills gap-2">
        <li class="nav-item">
            <a class="nav-link {{ ($activeTab ?? 'all') === 'all' ? 'active' : '' }}"
                href="{{ route('admin.bookings.index', ['tab' => 'all']) }}">
                Tất cả ({{ $allCount ?? 0 }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($activeTab ?? 'all') === 'pending-confirmation' ? 'active' : '' }}"
                href="{{ route('admin.bookings.index', ['tab' => 'pending-confirmation']) }}">
                Chờ xác nhận ({{ $pendingConfirmationCount ?? 0 }})
            </a>
        </li>
    </ul>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="tab" value="{{ $activeTab ?? 'all' }}">

            <div class="col-md-4">
                <label for="tour_id" class="form-label">Lọc theo tour</label>
                <select name="tour_id" id="tour_id" class="form-select">
                    <option value="">Tất cả tour</option>
                    @foreach ($tours ?? [] as $tour)
                    <option value="{{ $tour->tour_id }}" {{ (string) ($tourId ?? '') === (string) $tour->tour_id ? 'selected' : '' }}>
                        {{ $tour->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="date_from" class="form-label">Ngày đặt từ</label>
                <input type="date" name="date_from" id="date_from" class="form-control"
                    value="{{ $dateFrom ?? '' }}">
            </div>

            <div class="col-md-3">
                <label for="date_to" class="form-label">Đến ngày</label>
                <input type="date" name="date_to" id="date_to" class="form-control"
                    value="{{ $dateTo ?? '' }}">
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
                <a href="{{ route('admin.bookings.index', ['tab' => $activeTab ?? 'all']) }}"
                    class="btn btn-outline-secondary w-100">Xóa lọc</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">#</th>
                        <th class="py-3">Ngày đặt</th>
                        <th class="py-3">Tour</th>
                        <th class="py-3">Khách hàng</th>
                        <th class="py-3 text-center">Số khách</th>
                        <th class="py-3 text-end">Tổng tiền</th>
                        <th class="py-3">Trạng thái</th>
                        <th class="py-3">Thanh toán</th>
                        <th class="py-3">Xác nhận admin</th>
                        <th class="pe-4 py-3 text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td class="ps-4">#{{ $booking->booking_id }}</td>
                        <td>{{ $booking->booking_date ? $booking->booking_date->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $booking->tour->name ?? 'N/A' }}</td>
                        <td>{{ $booking->customer->fullname ?? 'N/A' }}</td>
                        <td class="text-center">{{ $booking->num_people }}</td>
                        <td class="text-end fw-bold text-success">
                            {{ number_format($booking->total_price, 0, ',', '.') }} ₫
                        </td>
                        <td>
                            <span
                                class="badge {{ $booking->status == 'upcoming' ? 'bg-info' : ($booking->status == 'ongoing' ? 'bg-primary' : ($booking->status == 'completed' ? 'bg-success' : 'bg-secondary')) }} text-white">{{ \App\Models\Booking::STATUS[$booking->status] ?? ucfirst($booking->status) }}</span>
                        </td>
                        <td>
                            <span
                                class="badge {{ $booking->payment_status == 'unpaid' ? 'bg-danger' : ($booking->payment_status == 'deposit' ? 'bg-warning text-dark' : 'bg-success') }} text-white">{{ \App\Models\Booking::PAYMENT_STATUS[$booking->payment_status] ?? ucfirst($booking->payment_status) }}</span>
                        </td>
                        <td>
                            @if ($booking->admin_confirmed)
                            <span class="badge bg-success">Đã xác nhận</span>
                            @elseif($booking->payment_status === 'paid')
                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                            @else
                            <span class="badge bg-secondary">Chưa đủ điều kiện</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group" role="group" aria-label="Booking actions">
                                @if ($booking->payment_status === 'paid' && !$booking->admin_confirmed)
                                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST"
                                    onsubmit="return confirm('Xác nhận booking này cho khách?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success"
                                        title="Xác nhận booking">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('admin.bookings.show', $booking) }}"
                                    class="btn btn-sm btn-outline-info" title="Chi tiết"><i
                                        class="fas fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Chưa có booking</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection