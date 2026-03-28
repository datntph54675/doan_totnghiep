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
                                    {{ number_format($booking->total_price, 0, ',', '.') }} ₫</td>
                                <td>
                                    <span
                                        class="badge {{ $booking->status == 'upcoming' ? 'bg-info' : ($booking->status == 'ongoing' ? 'bg-primary' : ($booking->status == 'completed' ? 'bg-success' : 'bg-secondary')) }} text-white">{{ \App\Models\Booking::STATUS[$booking->status] ?? ucfirst($booking->status) }}</span>
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $booking->payment_status == 'unpaid' ? 'bg-danger' : ($booking->payment_status == 'deposit' ? 'bg-warning text-dark' : 'bg-success') }} text-white">{{ \App\Models\Booking::PAYMENT_STATUS[$booking->payment_status] ?? ucfirst($booking->payment_status) }}</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.bookings.show', $booking) }}"
                                            class="btn btn-sm btn-outline-info" title="Chi tiết"><i
                                                class="fas fa-eye"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Chưa có booking</td>
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
