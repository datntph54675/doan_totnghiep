@extends('layouts.app')

@section('title', 'Chi tiết Booking')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Chi tiết Booking #{{ $booking->booking_id }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                            class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}"
                            class="text-decoration-none">Booking</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Thông tin chung</h5>
                    <dl class="row">
                        <dt class="col-sm-4">Mã Booking</dt>
                        <dd class="col-sm-8">#{{ $booking->booking_id }}</dd>

                        <dt class="col-sm-4">Ngày đặt</dt>
                        <dd class="col-sm-8">{{ $booking->booking_date->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Tour</dt>
                        <dd class="col-sm-8">{{ $booking->tour->name ?? '-' }}</dd>

                        <dt class="col-sm-4">Lịch</dt>
                        <dd class="col-sm-8">{{ $booking->schedule->start_date->format('d/m/Y') ?? '-' }} -
                            {{ $booking->schedule->end_date->format('d/m/Y') ?? '-' }}</dd>

                        <dt class="col-sm-4">Khách hàng</dt>
                        <dd class="col-sm-8">{{ $booking->customer->fullname ?? '-' }}
                            ({{ $booking->customer->phone ?? '-' }})</dd>

                        <dt class="col-sm-4">Số người</dt>
                        <dd class="col-sm-8">{{ $booking->num_people }}</dd>

                        <dt class="col-sm-4">Tổng tiền</dt>
                        <dd class="col-sm-8">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</dd>

                        <dt class="col-sm-4">Ghi chú</dt>
                        <dd class="col-sm-8">{{ $booking->note ?? '-' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Cập nhật trạng thái</h5>
                    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Trạng thái</label>
                            <div class="col-sm-9">
                                <select name="status" class="form-select" required>
                                    @foreach (\App\Models\Booking::STATUS as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ $booking->status === $value ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Thanh toán</label>
                            <div class="col-sm-9">
                                <select name="payment_status" class="form-select" required>
                                    @foreach (\App\Models\Booking::PAYMENT_STATUS as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ $booking->payment_status === $value ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Ghi chú</label>
                            <div class="col-sm-9">
                                <textarea name="note" class="form-control" rows="3">{{ old('note', $booking->note) }}</textarea>
                            </div>
                        </div>

                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
