@extends('layouts.app')

@section('title', 'Quản lý Phân công HDV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản lý Phân công HDV</h1>
    <a href="{{ route('admin.guide-assignments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Thêm phân công mới
    </a>
</div>

@if($assignments->isEmpty())
    <div class="alert alert-info">
        Chưa có phân công HDV nào. <a href="{{ route('admin.guide-assignments.create') }}">Tạo phân công mới</a>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tour</th>
                    <th>Lịch khởi hành</th>
                    <th>HDV</th>
                    <th>Phân công bởi</th>
                    <th>Ngày phân công</th>
                    <th>Trạng thái</th>
                    <th>Ghi chú</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->id }}</td>
                    <td>
                        <a href="{{ route('admin.tours.show', $assignment->schedule->tour->tour_id) }}">
                            {{ $assignment->schedule->tour->name }}
                        </a>
                    </td>
                    <td>
                        <small>
                            {{ \Illuminate\Support\Carbon::parse($assignment->schedule->start_date)->format('d/m/Y') }} - 
                            {{ \Illuminate\Support\Carbon::parse($assignment->schedule->end_date)->format('d/m/Y') }}
                        </small>
                    </td>
                    <td>
                        <a href="{{ route('admin.guides.show', $assignment->guide->guide_id) }}">
                            {{ $assignment->guide->user->fullname }}
                        </a>
                    </td>
                    <td>
                        @if($assignment->assigner)
                            {{ $assignment->assigner->fullname }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <small>{{ $assignment->assigned_at->format('d/m/Y H:i') }}</small>
                    </td>
                    <td>
                        @switch($assignment->status)
                            @case('active')
                                <span class="badge bg-success">Hoạt động</span>
                                @break
                            @case('completed')
                                <span class="badge bg-primary">Hoàn thành</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger">Hủy</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $assignment->status }}</span>
                        @endswitch
                    </td>
                    <td>
                        @if($assignment->note)
                            <small>{{ \Illuminate\Support\Str::limit($assignment->note, 30) }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.guide-assignments.show', $assignment->id) }}" 
                               class="btn btn-info btn-sm" title="Xem chi tiết">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                            <a href="{{ route('admin.guide-assignments.edit', $assignment->id) }}" 
                               class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <form action="{{ route('admin.guide-assignments.destroy', $assignment->id) }}" 
                                  method="POST" class="m-0" style="display:inline;"
                                  onsubmit="return confirm('Bạn chắc chắn muốn xóa không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $assignments->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection
