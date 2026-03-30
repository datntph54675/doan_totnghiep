@extends('layouts.app')

@section('title', 'Chỉnh sửa Phân công HDV')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Chỉnh sửa Phân công HDV</h1>
        </div>
    </div>

    <form action="{{ route('admin.guide-assignments.update', $guideAssignment->id) }}" method="POST" class="needs-validation">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="schedule_id" class="form-label">Lịch khởi hành <span class="text-danger">*</span></label>
                <select name="schedule_id" id="schedule_id" class="form-select @error('schedule_id') is-invalid @enderror" required>
                    <option value="">-- Chọn lịch khởi hành --</option>
                    @foreach($schedules as $schedule)
                    <option value="{{ $schedule->schedule_id }}" @selected($guideAssignment->schedule_id == $schedule->schedule_id)>
                        {{ $schedule->tour->name }} -
                        {{ \Illuminate\Support\Carbon::parse($schedule->start_date)->format('d/m/Y') }} đến {{ \Illuminate\Support\Carbon::parse($schedule->end_date)->format('d/m/Y') }}
                    </option>
                    @endforeach
                </select>
                @error('schedule_id')
                <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="guide_id" class="form-label">Hướng dẫn viên <span class="text-danger">*</span></label>
                <select name="guide_id" id="guide_id" class="form-select @error('guide_id') is-invalid @enderror" required>
                    <option value="">-- Chọn hướng dẫn viên --</option>
                    @foreach($guides as $guide)
                    <option value="{{ $guide->guide_id }}" @selected($guideAssignment->guide_id == $guide->guide_id)>
                        {{ $guide->user->fullname }} ({{ $guide->user->phone ?? 'N/A' }})
                    </option>
                    @endforeach
                </select>
                @error('guide_id')
                <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="note" class="form-label">Ghi chú</label>
            <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="3" placeholder="Nhập ghi chú nếu có...">{{ old('note', $guideAssignment->note) }}</textarea>
            @error('note')
            <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="alert alert-info">
            <small><strong>Thông tin phân công:</strong></small>
            <ul class="mb-0">
                <li>Phân công bởi: <strong>{{ $guideAssignment->assigner->fullname ?? 'N/A' }}</strong></li>
                <li>Ngày phân công: <strong>{{ $guideAssignment->assigned_at->format('d/m/Y H:i') }}</strong></li>
            </ul>
        </div>

        <div class="alert alert-secondary">
            <small><strong>Trạng thái xác nhận từ HDV:</strong></small>
            <ul class="mb-0">
                <li>Trạng thái: <strong>
                        @if($guideAssignment->status == 'pending')
                        <span class="badge bg-warning">Chờ xác nhận</span>
                        @elseif($guideAssignment->status == 'accepted')
                        <span class="badge bg-success">Đã chấp nhận</span>
                        @elseif($guideAssignment->status == 'rejected')
                        <span class="badge bg-danger">Từ chối</span>
                        @else
                        <span class="badge bg-secondary">{{ $guideAssignment->status }}</span>
                        @endif
                    </strong></li>
                @if($guideAssignment->confirmed_at)
                <li>Xác nhận lúc: <strong>{{ $guideAssignment->confirmed_at->format('d/m/Y H:i') }}</strong></li>
                @endif
                @if($guideAssignment->rejection_reason)
                <li>Lý do từ chối: <strong>{{ $guideAssignment->rejection_reason }}</strong></li>
                @endif
            </ul>
        </div>

        <div class="alert alert-warning">
            <small>Nếu đổi hướng dẫn viên hoặc đổi lịch khởi hành, hệ thống sẽ tự đặt lại trạng thái về <strong>Chờ xác nhận</strong> cho bản phân công mới.</small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.guide-assignments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Cập nhật
            </button>
        </div>
    </form>
</div>
@endsection