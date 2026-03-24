@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Sửa lịch xuất phát</h1>
    </div>

    <form action="{{ route('admin.tours.departure-schedules.update', ['tour' => $tour->tour_id, 'schedule' => $schedule->schedule_id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $schedule->start_date->format('Y-m-d')) }}" required>
                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Ngày kết thúc</label>
                <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $schedule->end_date->format('Y-m-d')) }}" required>
                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="max_people" class="form-label">Số khách tối đa</label>
                <input type="number" name="max_people" id="max_people" min="1" class="form-control @error('max_people') is-invalid @enderror" value="{{ old('max_people', $schedule->max_people) }}" required>
                @error('max_people')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="scheduled" @selected(old('status', $schedule->status) == 'scheduled')>scheduled</option>
                    <option value="ongoing" @selected(old('status', $schedule->status) == 'ongoing')>Đang diễn ra</option>
                    <option value="completed" @selected(old('status', $schedule->status) == 'completed')>Hoàn thành</option>
                    <option value="cancelled" @selected(old('status', $schedule->status) == 'cancelled')>Hủy</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="meeting_point" class="form-label">Điểm gặp</label>
            <input type="text" name="meeting_point" id="meeting_point" class="form-control @error('meeting_point') is-invalid @enderror" value="{{ old('meeting_point', $schedule->meeting_point) }}">
            @error('meeting_point')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Ghi chú</label>
            <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $schedule->notes) }}</textarea>
            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <a href="{{ route('admin.tours.departure-schedules.index', $tour->tour_id) }}" class="btn btn-secondary">Quay lại</a>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection