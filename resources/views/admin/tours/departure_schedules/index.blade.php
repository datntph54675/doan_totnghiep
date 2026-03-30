@extends('layouts.app')

@section('title', 'Lịch xuất phát - ' . $tour->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Lịch xuất phát của tour: {{ $tour->name }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}"
                        class="text-decoration-none">Tour</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours.show', $tour) }}"
                        class="text-decoration-none">{{ $tour->name }}</a></li>
                <li class="breadcrumb-item active">Lịch xuất phát</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tours.show', $tour) }}" class="btn btn-outline-secondary px-4">
            <i class="fas fa-arrow-left me-1"></i> Quay lại Tour
        </a>
        <a href="{{ route('admin.tours.departure-schedules.create', $tour->tour_id) }}"
            class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus me-2"></i> Thêm lịch xuất phát
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-muted small fw-bold" style="width: 80px;">ID</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Ngày bắt đầu</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Ngày kết thúc</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Số khách tối đa</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Điểm gặp</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">Trạng thái</th>
                        <th class="py-3 text-uppercase text-muted small fw-bold">HDV</th>
                        <th class="pe-4 py-3 text-uppercase text-muted small fw-bold text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tour->departureSchedules as $schedule)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-secondary">#{{ $schedule->schedule_id }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $schedule->start_date->format('d/m/Y') }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $schedule->end_date->format('d/m/Y') }}</div>
                        </td>
                        <td>
                            <span class="text-muted">{{ $schedule->max_people }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $schedule->meeting_point ?: '---' }}</span>
                        </td>
                        <td>
                            @if ($schedule->status == 'active' || $schedule->status == 1)
                            <span class="badge bg-success-subtle text-success px-3 py-2">Đang hoạt động</span>
                            @else
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Tạm ẩn</span>
                            @endif
                        </td>
                        <td>
                            @php
                            $assignedGuide = $schedule->guideAssignments
                            ->where('status', 'accepted')
                            ->sortByDesc('assigned_at')
                            ->first();

                            if (!$assignedGuide) {
                            $assignedGuide = $schedule->guideAssignments
                            ->where('status', 'pending')
                            ->sortByDesc('assigned_at')
                            ->first();
                            }

                            if (!$assignedGuide) {
                            $assignedGuide = $schedule->guideAssignments
                            ->sortByDesc('assigned_at')
                            ->first();
                            }
                            @endphp
                            <span
                                class="text-muted">{{ data_get($assignedGuide, 'guide.user.fullname', 'Chưa gán') }}</span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.tours.departure-schedules.edit', ['tour' => $tour->tour_id, 'schedule' => $schedule->schedule_id]) }}"
                                    class="btn btn-sm btn-outline-warning me-2" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.guide-assignments.index') }}?schedule_id={{ $schedule->schedule_id }}"
                                    class="btn btn-sm btn-outline-info me-2" title="Phân công HDV">
                                    <i class="fas fa-user"></i>
                                </a>
                                <form
                                    action="{{ route('admin.tours.departure-schedules.destroy', ['tour' => $tour->tour_id, 'schedule' => $schedule->schedule_id]) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <br>Không có lịch xuất phát nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($tour->departureSchedules instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="card-footer bg-white border-top-0 py-3">
                {{ $tour->departureSchedules->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection