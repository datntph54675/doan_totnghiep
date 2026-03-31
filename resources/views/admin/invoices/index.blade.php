@extends('layouts.app')

@section('title', 'Quản lý Hóa đơn')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Quản lý Hóa đơn</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Hóa đơn</li>
                        </ol>
                    </nav>
                </div>
            </div>

            {{-- Thống kê nhanh --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="fas fa-file-invoice-dollar text-success fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Tổng hóa đơn</div>
                                <div class="fw-bold fs-5">{{ number_format($totalCount) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="fas fa-coins text-primary fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Doanh thu (đã thanh toán)</div>
                                <div class="fw-bold fs-5 text-primary">{{ number_format($totalRevenue, 0, ',', '.') }} ₫</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bộ lọc --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <form action="{{ route('admin.invoices.index') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Tìm kiếm</label>
                            <input type="text" name="search" class="form-control" placeholder="Mã, tên KH, tour..."
                                value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Phương thức TT</label>
                            <select name="payment_method" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="vnpay" {{ ($payMethod ?? '') === 'vnpay' ? 'selected' : '' }}>VNPAY</option>
                                <option value="momo" {{ ($payMethod ?? '') === 'momo' ? 'selected' : '' }}>MoMo</option>
                                <option value="vietqr" {{ ($payMethod ?? '') === 'vietqr' ? 'selected' : '' }}>VietQR</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Trạng thái TT</label>
                            <select name="payment_status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="paid" {{ ($payStatus ?? '') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="deposit" {{ ($payStatus ?? '') === 'deposit' ? 'selected' : '' }}>Đặt cọc</option>
                                <option value="refunded" {{ ($payStatus ?? '') === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo ?? '' }}">
                        </div>
                        <div class="col-md-1 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        </div>
                        <div class="col-12">
                            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times me-1"></i>Xóa bộ lọc
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bảng danh sách --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">Mã HĐ</th>
                                    <th class="py-3">Ngày đặt</th>
                                    <th class="py-3">Khách hàng</th>
                                    <th class="py-3">Tour</th>
                                    <th class="py-3">Lịch khởi hành</th>
                                    <th class="py-3 text-center">Số khách</th>
                                    <th class="py-3 text-end">Tổng tiền</th>
                                    <th class="py-3">Phương thức</th>
                                    <th class="py-3">Trạng thái TT</th>
                                    <th class="py-3">Xác nhận</th>
                                    <th class="pe-4 py-3 text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-semibold text-primary">#{{ $invoice->booking_id }}</span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $invoice->booking_date ? $invoice->booking_date->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $invoice->customer->fullname ?? 'N/A' }}</div>
                                        <div class="text-muted small">{{ $invoice->customer->phone ?? '' }}</div>
                                    </td>
                                    <td>{{ $invoice->tour->name ?? 'N/A' }}</td>
                                    <td class="small text-muted">
                                        @if($invoice->schedule)
                                            {{ $invoice->schedule->start_date->format('d/m/Y') }}
                                            – {{ $invoice->schedule->end_date->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $invoice->num_people }}</td>
                                    <td class="text-end fw-bold text-success">
                                        {{ number_format($invoice->total_price, 0, ',', '.') }} ₫
                                    </td>
                                    <td>
                                        @php
                                            $methodLabels = [
                                                'vnpay'  => ['label' => 'VNPAY',  'class' => 'bg-info'],
                                                'momo'   => ['label' => 'MoMo',   'class' => 'bg-danger'],
                                                'vietqr' => ['label' => 'VietQR', 'class' => 'bg-warning text-dark'],
                                            ];
                                            $m = $methodLabels[$invoice->payment_method] ?? null;
                                        @endphp
                                        @if($m)
                                            <span class="badge {{ $m['class'] }}">{{ $m['label'] }}</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($invoice->payment_status === 'paid')
                                            <span class="badge bg-success">Đã thanh toán</span>
                                        @elseif($invoice->payment_status === 'deposit')
                                            <span class="badge bg-warning text-dark">Đặt cọc</span>
                                        @elseif($invoice->payment_status === 'refunded')
                                            <span class="badge bg-secondary">Đã hoàn tiền</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($invoice->admin_confirmed)
                                            <span class="badge bg-success">Đã xác nhận</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('admin.invoices.show', $invoice->booking_id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Xem hóa đơn">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">Không có hóa đơn nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($invoices->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    {{ $invoices->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
