@extends('layouts.app')

@section('title', 'Quản lý Phân công HDV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Quản lý Phân công HDV</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Phân công HDV</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.guide-assignments.create') }}" class="btn btn-primary shadow-sm px-4">
        <i class="fas fa-plus me-2"></i> Thêm phân công mới
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
                        <th class="py-3 text-uppercase text-muted small fw-bold">Lịch khởi hành</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">HDV</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Phân công bởi</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Ngày phân công</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Trạng thái</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Ghi chú</th>
                        <th class="pe-4 py-3 text-uppercase text-muted small fw-bold text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-secondary">#{{ $assignment->id }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">
                                <a href="{{ route('admin.tours.show', $assignment->schedule->tour->tour_id) }}" class="text-decoration-none">
                                    {{ $assignment->schedule->tour->name }}
                                </a>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted">
                                {{ \Illuminate\Support\Carbon::parse($assignment->schedule->start_date)->format('d/m/Y') }} -
                                {{ \Illuminate\Support\Carbon::parse($assignment->schedule->end_date)->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.guides.show', $assignment->guide->guide_id) }}" class="text-decoration-none fw-bold text-primary">
                                {{ $assignment->guide->user->fullname }}
                            </a>
                        </td>
                        <td>
                            <span class="text-muted">{{ $assignment->assigner ? $assignment->assigner->fullname : '—' }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $assignment->assigned_at->format('d/m/Y H:i') }}</span>
                        </td>
                        <td>
                            @switch($assignment->status)
                            @case('pending')
                            <span class="badge bg-warning-subtle text-warning px-3 py-2">Chờ xác nhận</span>
                            @break
                            @case('accepted')
                            <span class="badge bg-success-subtle text-success px-3 py-2">Đã chấp nhận</span>
                            @break
                            @case('rejected')
                            <span class="badge bg-danger-subtle text-danger px-3 py-2">Từ chối</span>
                            @break
                            @case('completed')
                            <span class="badge bg-primary-subtle text-primary px-3 py-2">Hoàn thành</span>
                            @break
                            @case('cancelled')
                            <span class="badge bg-danger-subtle text-danger px-3 py-2">Hủy</span>
                            @break
                            @default
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2">{{ $assignment->status }}</span>
                            @endswitch
                        </td>
                        <td>
                            <span class="text-muted">{{ $assignment->note ? \Illuminate\Support\Str::limit($assignment->note, 30) : '—' }}</span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.guide-assignments.show', $assignment->id) }}"
                                    class="btn btn-sm btn-outline-info me-2" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.guide-assignments.edit', $assignment->id) }}"
                                    class="btn btn-sm btn-outline-warning me-2" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.guide-assignments.destroy', $assignment->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-users-cog fa-2x mb-2"></i>
                            <br>Chưa có phân công HDV nào. <a href="{{ route('admin.guide-assignments.create') }}" class="text-decoration-none">Tạo phân công mới</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($assignments instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $assignments->links() }}
    </div>
    @endif
</div>
@endsection