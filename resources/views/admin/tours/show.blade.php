@extends('admin.layout')

@section('content')
<div style="margin-bottom:16px;display:flex;align-items:center;justify-content:space-between">
    <h2 style="margin:0">Chi tiết Tour</h2>
    <div style="display:flex;gap:8px">
        <a href="{{ route('admin.tours.edit', $tour->tour_id) }}" class="btn-logout" style="background:#0f62fe">Chỉnh sửa</a>
        <a href="{{ route('admin.tours.index') }}" class="btn-logout" style="background:#6b7280">← Quay lại</a>
    </div>
</div>

{{-- Thông tin chính --}}
<div class="card" style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    {{-- Ảnh --}}
    <div style="grid-column:1/-1">
        @if($tour->image)
            <img src="{{ Storage::url($tour->image) }}" alt="{{ $tour->name }}"
                 style="width:100%;max-height:320px;object-fit:cover;border-radius:10px">
        @else
            <div style="width:100%;height:160px;background:#f1f5f9;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#6b7280;font-size:14px">
                Không có hình ảnh
            </div>
        @endif
    </div>

    <div>
        <p class="muted-sm" style="margin:0 0 4px">ID</p>
        <p style="margin:0;font-weight:600">#{{ $tour->tour_id }}</p>
    </div>
    <div>
        <p class="muted-sm" style="margin:0 0 4px">Tên tour</p>
        <p style="margin:0;font-weight:600">{{ $tour->name }}</p>
    </div>
    <div>
        <p class="muted-sm" style="margin:0 0 4px">Danh mục</p>
        <p style="margin:0">{{ $tour->category ? $tour->category->name : '—' }}</p>
    </div>
    <div>
        <p class="muted-sm" style="margin:0 0 4px">Nhà cung cấp</p>
        <p style="margin:0">{{ $tour->supplier ?: '—' }}</p>
    </div>
    <div>
        <p class="muted-sm" style="margin:0 0 4px">Giá</p>
        <p style="margin:0;font-weight:700;color:#0f62fe;font-size:18px">{{ number_format($tour->price, 0, ',', '.') }} VND</p>
    </div>
    <div>
        <p class="muted-sm" style="margin:0 0 4px">Số người tối đa</p>
        <p style="margin:0">{{ $tour->max_people }} người</p>
    </div>
    <div>
        <p class="muted-sm" style="margin:0 0 4px">Thời gian</p>
        <p style="margin:0">{{ $tour->duration ? $tour->duration . ' ngày' : '—' }}</p>
    </div>
    <div>
        <p class="muted-sm" style="margin:0 0 4px">Trạng thái</p>
        <p style="margin:0">
            @if($tour->status === 'active')
                <span style="background:#dcfce7;color:#16a34a;padding:3px 10px;border-radius:20px;font-size:13px">Hoạt động</span>
            @else
                <span style="background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:13px">Ngừng hoạt động</span>
            @endif
        </p>
    </div>
    <div>
        <p class="muted-sm" style="margin:0 0 4px">Ngày bắt đầu</p>
        <p style="margin:0">{{ $tour->start_date ?: '—' }}</p>
    </div>
    <div>
        <p class="muted-sm" style="margin:0 0 4px">Ngày kết thúc</p>
        <p style="margin:0">{{ $tour->end_date ?: '—' }}</p>
    </div>

    @if($tour->description)
    <div style="grid-column:1/-1">
        <p class="muted-sm" style="margin:0 0 4px">Mô tả</p>
        <p style="margin:0;line-height:1.7">{{ $tour->description }}</p>
    </div>
    @endif

    @if($tour->policy)
    <div style="grid-column:1/-1">
        <p class="muted-sm" style="margin:0 0 4px">Chính sách</p>
        <p style="margin:0;line-height:1.7">{{ $tour->policy }}</p>
    </div>
    @endif
</div>

{{-- Lịch trình --}}
@if($tour->itineraries->count())
<div class="card" style="margin-top:20px">
    <h3 style="margin:0 0 14px">Lịch trình ({{ $tour->itineraries->count() }} ngày)</h3>
    @foreach($tour->itineraries->sortBy('day_number') as $item)
    <div style="border-left:3px solid #0f62fe;padding:8px 14px;margin-bottom:10px;background:#f6fbff;border-radius:0 8px 8px 0">
        <p style="font-weight:600;margin:0">
            Ngày {{ $item->day_number }}{{ $item->title ? ': ' . $item->title : '' }}
            @if($item->time_start)
                <span class="muted-sm" style="font-weight:400;margin-left:8px">{{ $item->time_start }}{{ $item->time_end ? ' – ' . $item->time_end : '' }}</span>
            @endif
        </p>
        @if($item->location)
            <p style="margin:4px 0 0;font-size:13px;color:#0f62fe">📍 {{ $item->location }}</p>
        @endif
        @if($item->description)
            <p style="margin:4px 0 0;color:#6b7280;font-size:14px">{{ $item->description }}</p>
        @endif
    </div>
    @endforeach
</div>
@endif

{{-- Lịch khởi hành --}}
@if($tour->departureSchedules->count())
<div class="card" style="margin-top:20px">
    <h3 style="margin:0 0 14px">Lịch khởi hành ({{ $tour->departureSchedules->count() }} chuyến)</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Ngày đi</th>
                <th>Ngày về</th>
                <th>Điểm tập kết</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tour->departureSchedules as $schedule)
            <tr>
                <td>{{ $schedule->start_date }}</td>
                <td>{{ $schedule->end_date }}</td>
                <td>{{ $schedule->meeting_point ?: '—' }}</td>
                <td>
                    @php
                        $statusMap = [
                            'scheduled'  => ['label' => 'Đã lên lịch', 'color' => '#2563eb', 'bg' => '#dbeafe'],
                            'ongoing'    => ['label' => 'Đang diễn ra', 'color' => '#d97706', 'bg' => '#fef3c7'],
                            'completed'  => ['label' => 'Hoàn thành',  'color' => '#16a34a', 'bg' => '#dcfce7'],
                            'cancelled'  => ['label' => 'Đã hủy',      'color' => '#dc2626', 'bg' => '#fee2e2'],
                        ];
                        $s = $statusMap[$schedule->status] ?? ['label' => $schedule->status, 'color' => '#6b7280', 'bg' => '#f1f5f9'];
                    @endphp
                    <span style="background:{{ $s['bg'] }};color:{{ $s['color'] }};padding:3px 10px;border-radius:20px;font-size:12px">
                        {{ $s['label'] }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
