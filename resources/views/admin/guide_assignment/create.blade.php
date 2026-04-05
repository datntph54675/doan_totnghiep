@extends('layouts.app')

@section('title', 'Thêm Phân công HDV')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Thêm Phân công HDV</h1>
        </div>
    </div>

    <form action="{{ route('admin.guide-assignments.store') }}" method="POST" class="needs-validation">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="schedule_id" class="form-label">Lịch khởi hành <span class="text-danger">*</span></label>
                <select name="schedule_id" id="schedule_id"
                    class="form-select @error('schedule_id') is-invalid @enderror" required>
                    <option value="">-- Chọn lịch khởi hành --</option>
                    @foreach ($schedules as $schedule)
                    <option value="{{ $schedule->schedule_id }}" @selected(old('schedule_id')==$schedule->schedule_id)>
                        {{ $schedule->tour->name }} -
                        {{ \Illuminate\Support\Carbon::parse($schedule->start_date)->format('d/m/Y') }} đến
                        {{ \Illuminate\Support\Carbon::parse($schedule->end_date)->format('d/m/Y') }}
                    </option>
                    @endforeach
                </select>
                @error('schedule_id')
                <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="guide_id" class="form-label">Hướng dẫn viên <span class="text-danger">*</span></label>
                <select name="guide_id" id="guide_id" class="form-select @error('guide_id') is-invalid @enderror"
                    required>
                    <option value="">-- Chọn hướng dẫn viên --</option>
                    @foreach ($guides as $guide)
                    <option value="{{ $guide->guide_id }}" @selected(old('guide_id')==$guide->guide_id)>
                        {{ $guide->user->fullname }} ({{ $guide->user->phone ?? 'N/A' }})
                    </option>
                    @endforeach
                </select>
                @error('guide_id')
                <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
                <div id="guide-conflict-note" class="form-text text-danger d-none">
                    Một số HDV đang bận lịch trùng ngày nên đã bị khóa lựa chọn.
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="note" class="form-label">Ghi chú</label>
            <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="3"
                placeholder="Nhập ghi chú nếu có...">{{ old('note') }}</textarea>
            @error('note')
            <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.guide-assignments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Lưu
            </button>
        </div>
    </form>
</div>

<script id="guide-conflicts-data" type="application/json">
    {
        !!json_encode($guideConflictsBySchedule ?? []) !!
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scheduleSelect = document.getElementById('schedule_id');
        const guideSelect = document.getElementById('guide_id');
        const note = document.getElementById('guide-conflict-note');
        const conflictsBySchedule = JSON.parse(document.getElementById('guide-conflicts-data').textContent || '{}');

        function updateGuideAvailability() {
            const scheduleId = scheduleSelect.value;
            const busyGuideIds = new Set((conflictsBySchedule[scheduleId] || []).map(String));
            let hasBusyGuide = false;

            Array.from(guideSelect.options).forEach(function(option) {
                if (!option.value) {
                    return;
                }

                if (!option.dataset.baseText) {
                    option.dataset.baseText = option.textContent.replace(/\s*\(Bận trùng lịch\)$/u, '');
                }

                const isBusy = busyGuideIds.has(option.value);
                option.disabled = isBusy;
                option.textContent = isBusy ? option.dataset.baseText + ' (Bận trùng lịch)' : option.dataset.baseText;

                if (isBusy) {
                    hasBusyGuide = true;
                }

                if (isBusy && option.selected) {
                    option.selected = false;
                    guideSelect.value = '';
                }
            });

            note.classList.toggle('d-none', !(scheduleId && hasBusyGuide));
        }

        scheduleSelect.addEventListener('change', updateGuideAvailability);
        updateGuideAvailability();
    });
</script>
@endsection