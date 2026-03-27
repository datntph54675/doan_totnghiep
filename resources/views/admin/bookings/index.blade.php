@extends('layouts.app')

@section('title', 'Quản lý Đặt tour')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Quản lý Đặt tour</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Đặt tour</li>
            </ol>
        </nav>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}"
        class="btn {{ $status === 'pending' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
        Chờ xác nhận ({{ $pendingCount }})
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'confirmed']) }}"
        class="btn {{ $status === 'confirmed' ? 'btn-success' : 'btn-outline-success' }} btn-sm">
        Đã xác nhận ({{ $confirmedCount }})
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'all']) }}"
        class="btn {{ $status === 'all' ? 'btn-dark' : 'btn-outline-dark' }} btn-sm">
        Tất cả ({{ $pendingCount + $confirmedCount }})
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-muted small fw-bold" style="width: 80px;">ID</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Tour</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Ngày khởi hành</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Khách</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Số người</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Tổng tiền</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Đặt lúc</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Trạng thái</th>
                        <th class="pe-4 py-3 text-uppercase text-muted small fw-bold text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                    <tr>
                        <td class="ps-4"><span class="fw-bold text-secondary">#{{ $b->booking_id }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $b->tour->name ?? '—' }}</div>
                        </td>
                        <td>{{ $b->schedule && $b->schedule->start_date ? $b->schedule->start_date->format('d/m/Y') : '—' }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $b->customer->fullname ?? '—' }}</div>
                            <small class="text-muted">{{ $b->customer->phone ?? '' }}</small>
                        </td>
                        <td>{{ $b->num_people }}</td>
                        <td><span class="fw-bold text-success">{{ number_format($b->total_price, 0, ',', '.') }} VND</span></td>
                        <td>{{ $b->booking_date ? $b->booking_date->format('d/m/Y H:i') : '—' }}</td>
                        <td>
                            @if($b->admin_confirmed)
                            <span class="badge bg-success-subtle text-success px-3 py-2">Đã xác nhận</span>
                            @else
                            <span class="badge bg-warning-subtle text-warning px-3 py-2">Chờ xác nhận</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.bookings.show', $b->booking_id) }}" class="btn btn-sm btn-outline-info me-2" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$b->admin_confirmed)
                                <form action="{{ route('admin.bookings.confirm', $b->booking_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận đơn này?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Xác nhận">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @else
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            @if($status === 'confirmed')
                            Không có đơn nào đã xác nhận.
                            @elseif($status === 'all')
                            Chưa có đơn đặt tour nào.
                            @else
                            Không có đơn nào chờ xác nhận.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection