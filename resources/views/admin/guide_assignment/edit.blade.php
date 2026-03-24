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

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" @selected($guideAssignment->status == 'active')>Hoạt động</option>
                    <option value="cancelled" @selected($guideAssignment->status == 'cancelled')>Hủy</option>
                    <option value="completed" @selected($guideAssignment->status == 'completed')>Hoàn thành</option>
                </select>
                @error('status')
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

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Cập nhật
            </button>
            <a href="{{ route('admin.guide-assignments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
