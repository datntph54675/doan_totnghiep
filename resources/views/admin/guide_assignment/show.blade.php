@extends('layouts.app')

@section('title', 'Chi tiết Phân công HDV')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Chi tiết Phân công HDV</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Thông tin phân công</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Tour</h6>
                    <p>
                        <a href="{{ route('admin.tours.show', $guideAssignment->schedule->tour->tour_id) }}">
                            {{ $guideAssignment->schedule->tour->name }}
                        </a>
                    </p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Hướng dẫn viên</h6>
                    <p>
                        <a href="{{ route('admin.guides.show', $guideAssignment->guide->guide_id) }}">
                            {{ $guideAssignment->guide->user->fullname }}
                        </a>
                        @if ($guideAssignment->guide->user->phone)
                        <br><small class="text-muted">{{ $guideAssignment->guide->user->phone }}</small>
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Lịch khởi hành</h6>
                    <p>
                        {{ \Illuminate\Support\Carbon::parse($guideAssignment->schedule->start_date)->format('d/m/Y') }}
                        -
                        {{ \Illuminate\Support\Carbon::parse($guideAssignment->schedule->end_date)->format('d/m/Y') }}
                        <br>
                        <small
                            class="text-muted">{{ \Illuminate\Support\Carbon::parse($guideAssignment->schedule->start_date)->diffForHumans() }}</small>
                    </p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Điểm gặp</h6>
                    <p>{{ $guideAssignment->schedule->meeting_point ?? 'Chưa có' }}</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Trạng thái</h6>
                    <p>
                        @switch($guideAssignment->status)
                        @case('pending')
                        <span class="badge bg-warning">Chờ xác nhận</span>
                        @break

                        @case('accepted')
                        <span class="badge bg-success">Đã chấp nhận</span>
                        @break

                        @case('rejected')
                        <span class="badge bg-danger">Từ chối</span>
                        @break

                        @case('completed')
                        <span class="badge bg-primary">Hoàn thành</span>
                        @break

                        @case('cancelled')
                        <span class="badge bg-danger">Hủy</span>
                        @break

                        @default
                        <span class="badge bg-secondary">{{ $guideAssignment->status }}</span>
                        @endswitch
                    </p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Ngày phân công</h6>
                    <p>
                        {{ \Illuminate\Support\Carbon::parse($guideAssignment->assigned_at)->format('d/m/Y H:i') }}
                        <br>
                        <small
                            class="text-muted">{{ \Illuminate\Support\Carbon::parse($guideAssignment->assigned_at)->diffForHumans() }}</small>
                    </p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Phân công bởi</h6>
                    <p>
                        @if ($guideAssignment->assigner)
                        {{ $guideAssignment->assigner->fullname }}
                        @if ($guideAssignment->assigner->email)
                        <br><small class="text-muted">{{ $guideAssignment->assigner->email }}</small>
                        @endif
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
            </div>

            @if ($guideAssignment->note)
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="text-muted">Ghi chú</h6>
                    <p>{{ $guideAssignment->note }}</p>
                </div>
            </div>
            @endif

            <div class="alert alert-info">
                <h6 class="text-muted">Xác nhận từ HDV</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Trạng thái xác nhận:</strong>
                        @switch($guideAssignment->status)
                        @case('pending')
                        <span class="badge bg-warning">Chờ xác nhận</span>
                        @break
                        @case('accepted')
                        <span class="badge bg-success">Đã chấp nhận</span>
                        @break
                        @case('rejected')
                        <span class="badge bg-danger">Từ chối</span>
                        @break
                        @case('cancelled')
                        <span class="badge bg-secondary">Hủy</span>
                        @break
                        @case('completed')
                        <span class="badge bg-primary">Hoàn thành</span>
                        @break
                        @default
                        <span class="badge bg-secondary">{{ $guideAssignment->status }}</span>
                        @endswitch
                    </div>
                    @if($guideAssignment->confirmed_at)
                    <div class="col-md-6">
                        <strong>Thời gian xác nhận:</strong> {{ $guideAssignment->confirmed_at->format('d/m/Y H:i') }}
                    </div>
                    @endif
                </div>
                @if($guideAssignment->rejection_reason)
                <div class="row">
                    <div class="col-12">
                        <strong>Lý do từ chối:</strong>
                        <p>{{ $guideAssignment->rejection_reason }}</p>
                    </div>
                </div>
                @endif
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Tạo lúc</h6>
                    <p><small>{{ $guideAssignment->created_at->format('d/m/Y H:i:s') }}</small></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Cập nhật lúc</h6>
                    <p><small>{{ $guideAssignment->updated_at->format('d/m/Y H:i:s') }}</small></p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('admin.guide-assignments.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
        <a href="{{ route('admin.guide-assignments.edit', $guideAssignment->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Chỉnh sửa
        </a>
        <form action="{{ route('admin.guide-assignments.destroy', $guideAssignment->id) }}" method="POST"
            style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa không?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Xóa
            </button>
        </form>

    </div>
</div>
@endsection