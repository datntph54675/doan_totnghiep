@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Lịch xuất phát của tour: {{ $tour->name }}</h1>
        <a href="{{ route('admin.tours.show', $tour->tour_id) }}" class="btn btn-secondary">Quay lại Tour</a>
    </div>

    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('admin.tours.departure-schedules.create', $tour->tour_id) }}" class="btn btn-success">Thêm lịch xuất phát</a>
        <a href="{{ route('admin.guide-assignments.index') }}" class="btn btn-primary">Xem Phân công HDV</a>
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Số khách tối đa</th>
                <th>Điểm gặp</th>
                <th>Trạng thái</th>
                <th>HDV</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tour->departureSchedules as $schedule)
            <tr>
                <td>{{ $schedule->schedule_id }}</td>
                <td>{{ $schedule->start_date->format('d/m/Y') }}</td>
                <td>{{ $schedule->end_date->format('d/m/Y') }}</td>
                <td>{{ $schedule->max_people }}</td>
                <td>{{ $schedule->meeting_point ?? '---' }}</td>
                <td>{{ $schedule->status }}</td>
                @php
                    $assignedGuide = $schedule->guideAssignments
                        ->where('status', 'active')
                        ->sortByDesc('assigned_at')
                        ->first();

                    if (!$assignedGuide) {
                        $assignedGuide = $schedule->guideAssignments
                            ->sortByDesc('assigned_at')
                            ->first();
                    }
                @endphp
                <td>{{ optional(optional($assignedGuide)->guide->user)->fullname ?? 'Chưa gán' }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.tours.departure-schedules.edit', ['tour' => $tour->tour_id, 'schedule' => $schedule->schedule_id]) }}" class="btn btn-sm btn-warning">Sửa</a>

                        <form action="{{ route('admin.tours.departure-schedules.destroy', ['tour' => $tour->tour_id, 'schedule' => $schedule->schedule_id]) }}" method="POST" onsubmit="return confirm('Xóa lịch xuất phát này?');" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>

                        <a href="{{ route('admin.guide-assignments.index') }}?schedule_id={{ $schedule->schedule_id }}" class="btn btn-sm btn-info">Phân công HDV</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">Không có lịch xuất phát nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
