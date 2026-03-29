@extends('layouts.app')

@section('title', 'Chỉnh sửa Lịch xuất phát')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}"
                        class="text-decoration-none">Tour</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours.show', $tour) }}"
                        class="text-decoration-none">{{ $tour->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours.departure-schedules.index', $tour) }}"
                        class="text-decoration-none">Lịch xuất phát</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>
        <h2 class="fw-bold text-dark">Sửa Lịch xuất phát</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.tours.departure-schedules.update', ['tour' => $tour->tour_id, 'schedule' => $schedule->schedule_id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="start_date" class="form-label fw-bold text-secondary">Ngày bắt đầu</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $schedule->start_date->format('Y-m-d')) }}"
                            class="form-control @error('start_date') is-invalid @enderror" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="end_date" class="form-label fw-bold text-secondary">Ngày kết thúc</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $schedule->end_date->format('Y-m-d')) }}"
                            class="form-control @error('end_date') is-invalid @enderror" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="max_people" class="form-label fw-bold text-secondary">Số khách tối đa</label>
                        <input type="number" name="max_people" id="max_people" min="1" value="{{ old('max_people', $schedule->max_people) }}"
                            class="form-control @error('max_people') is-invalid @enderror" placeholder="Nhập số khách..." required>
                        @error('max_people')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="status" class="form-label fw-bold text-secondary">Trạng thái</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="scheduled" {{ old('status', $schedule->status) == 'scheduled' ? 'selected' : '' }}>Đã lên lịch</option>
                            <option value="ongoing" {{ old('status', $schedule->status) == 'ongoing' ? 'selected' : '' }}>Đang diễn ra</option>
                            <option value="completed" {{ old('status', $schedule->status) == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>Hủy</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label for="meeting_point" class="form-label fw-bold text-secondary">Điểm gặp</label>
                        <input type="text" name="meeting_point" id="meeting_point" value="{{ old('meeting_point', $schedule->meeting_point) }}"
                            class="form-control @error('meeting_point') is-invalid @enderror" placeholder="Nhập điểm gặp...">
                        @error('meeting_point')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label for="notes" class="form-label fw-bold text-secondary">Ghi chú</label>
                        <textarea name="notes" id="notes" rows="4"
                            class="form-control @error('notes') is-invalid @enderror"
                            placeholder="Nhập ghi chú...">{{ old('notes', $schedule->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        Cập nhật
                    </button>
                    <a href="{{ route('admin.tours.departure-schedules.index', $tour->tour_id) }}" class="btn btn-outline-secondary px-4">
                        Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
