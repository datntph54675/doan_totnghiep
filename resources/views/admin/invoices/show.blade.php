@extends('layouts.app')

@section('title', 'Hóa đơn #' . $booking->booking_id)

@section('css')
<style>
    @media print {
        .no-print { display: none !important; }
        .invoice-box { box-shadow: none !important; border: 1px solid #ddd; }
    }
    .invoice-box { background: #fff; max-width: 820px; margin: 0 auto; padding: 32px; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
    .invoice-header { border-bottom: 2px solid #0f62fe; padding-bottom: 20px; margin-bottom: 24px; }
    .badge-method { font-size: .85rem; padding: .4em .8em; }
    .total-row td { font-size: 1.1rem; }
</style>
@endsection

@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            {{-- Breadcrumb + actions --}}
            <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                <div>
                    <h4 class="fw-bold mb-1">Hóa đơn #{{ $booking->booking_id }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.invoices.index') }}">Hóa đơn</a></li>
                            <li class="breadcrumb-item active">#{{ $booking->booking_id }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary no-print">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button onclick="window.print()" class="btn btn-primary no-print">
                        <i class="fas fa-print me-1"></i> In hóa đơn
                    </button>
                </div>
            </div>

            {{-- Invoice Box --}}
            <div class="invoice-box">

                {{-- Header --}}
                <div class="invoice-header d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="fw-bold text-primary mb-1">HÓA ĐƠN THANH TOÁN</h3>
                        <div class="text-muted small">GoTour – Dịch vụ du lịch</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold fs-5">#{{ str_pad($booking->booking_id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-muted small">Ngày đặt: {{ $booking->booking_date->format('d/m/Y H:i') }}</div>
                        @php
                            $statusClass = match($booking->payment_status) {
                                'paid'     => 'bg-success',
                                'deposit'  => 'bg-warning text-dark',
                                'refunded' => 'bg-secondary',
                                default    => 'bg-danger',
                            };
                            $statusLabel = \App\Models\Booking::PAYMENT_STATUS[$booking->payment_status] ?? $booking->payment_status;
                        @endphp
                        <span class="badge {{ $statusClass }} badge-method mt-1">{{ $statusLabel }}</span>
                    </div>
                </div>

                {{-- Thông tin khách hàng & tour --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.75rem;letter-spacing:.08em">Thông tin khách hàng</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr><td class="text-muted" style="width:120px">Họ tên</td><td class="fw-semibold">{{ $booking->customer->fullname ?? '-' }}</td></tr>
                            <tr><td class="text-muted">Điện thoại</td><td>{{ $booking->customer->phone ?? '-' }}</td></tr>
                            <tr><td class="text-muted">Email</td><td>{{ $booking->customer->email ?? '-' }}</td></tr>
                            <tr><td class="text-muted">CCCD/CMND</td><td>{{ $booking->customer->id_number ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.75rem;letter-spacing:.08em">Thông tin tour</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr><td class="text-muted" style="width:120px">Tên tour</td><td class="fw-semibold">{{ $booking->tour->name ?? '-' }}</td></tr>
                            @if($booking->schedule)
                            <tr><td class="text-muted">Khởi hành</td><td>{{ $booking->schedule->start_date->format('d/m/Y') }}</td></tr>
                            <tr><td class="text-muted">Kết thúc</td><td>{{ $booking->schedule->end_date->format('d/m/Y') }}</td></tr>
                            <tr><td class="text-muted">Điểm tập kết</td><td>{{ $booking->schedule->meeting_point ?? '-' }}</td></tr>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- Chi tiết thanh toán --}}
                <h6 class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.75rem;letter-spacing:.08em">Chi tiết thanh toán</h6>
                <table class="table table-bordered mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>Dịch vụ</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $booking->tour->name ?? 'Tour du lịch' }}</td>
                            <td class="text-center">{{ $booking->num_people }} người</td>
                            <td class="text-end">
                                @if($booking->tour && $booking->num_people > 0)
                                    {{ number_format($booking->total_price / $booking->num_people, 0, ',', '.') }} ₫
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end fw-semibold">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="3" class="text-end fw-bold">Tổng cộng</td>
                            <td class="text-end fw-bold text-success fs-5">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</td>
                        </tr>
                    </tfoot>
                </table>

                {{-- Thông tin giao dịch --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.75rem;letter-spacing:.08em">Thông tin giao dịch</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width:160px">Phương thức</td>
                                <td>
                                    @php
                                        $methodMap = ['vnpay' => 'VNPAY', 'momo' => 'MoMo', 'vietqr' => 'VietQR (Chuyển khoản)'];
                                    @endphp
                                    {{ $methodMap[$booking->payment_method] ?? ($booking->payment_method ?? '-') }}
                                </td>
                            </tr>
                            @if($booking->vnp_transaction_no)
                            <tr>
                                <td class="text-muted">Mã giao dịch</td>
                                <td class="font-monospace small">{{ $booking->vnp_transaction_no }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-muted">Trạng thái booking</td>
                                <td>
                                    <span class="badge {{ $booking->status === 'cancelled' ? 'bg-secondary' : ($booking->status === 'completed' ? 'bg-success' : 'bg-info') }}">
                                        {{ \App\Models\Booking::STATUS[$booking->status] ?? $booking->status }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Xác nhận admin</td>
                                <td>
                                    @if($booking->admin_confirmed)
                                        <span class="badge bg-success">Đã xác nhận</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Chưa xác nhận</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    @if($booking->note)
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.75rem;letter-spacing:.08em">Ghi chú</h6>
                        <p class="text-muted small mb-0" style="white-space:pre-line">{{ $booking->note }}</p>
                    </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="border-top pt-3 text-center text-muted small">
                    <p class="mb-0">Cảm ơn quý khách đã sử dụng dịch vụ của GoTour.</p>
                    <p class="mb-0">Mọi thắc mắc vui lòng liên hệ hotline hoặc email hỗ trợ.</p>
                </div>

            </div>
            {{-- End Invoice Box --}}

        </div>
    </div>
</div>
@endsection
