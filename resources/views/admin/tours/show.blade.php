@extends('layouts.app')

@section('title', 'Chi tiết Tour: ' . $tour->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark m-0">Chi tiết Tour</h2>
        <p class="text-muted small mb-0">Quản lý và xem thông tin chi tiết hệ thống</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary px-3">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
        <a href="{{ route('admin.tours.edit', $tour->tour_id) }}" class="btn btn-primary px-3 shadow-sm">
            <i class="fas fa-edit me-1"></i> Chỉnh sửa
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row align-items-start">
                    <div class="col-md-3 mb-3 mb-md-0">
                        @if($tour->image)
                            @php
                                $imageUrl = Str::startsWith($tour->image, ['http://', 'https://']) ? $tour->image : asset($tour->image);
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $tour->name }}" 
                                 class="img-fluid rounded-3 shadow-sm" 
                                 style="width: 100%; height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height: 200px;">
                                <span class="text-muted small">Không có ảnh</span>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-9">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="text-muted small text-uppercase fw-bold d-block">Mã Tour</label>
                                <span class="text-dark fw-bold">#{{ $tour->tour_id }}</span>
                            </div>
                            <div class="col-md-8">
                                <label class="text-muted small text-uppercase fw-bold d-block">Tên Tour</label>
                                <span class="text-dark fw-bold fs-5">{{ $tour->name }}</span>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="text-muted small text-uppercase fw-bold d-block border-top pt-2">Danh mục</label>
                                <span class="badge bg-info-subtle text-info px-3">{{ $tour->category ? $tour->category->name : '—' }}</span>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small text-uppercase fw-bold d-block border-top pt-2">Nhà cung cấp</label>
                                <span class="text-dark">{{ $tour->supplier ?: '—' }}</span>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small text-uppercase fw-bold d-block border-top pt-2">Trạng thái</label>
                                @if($tour->status === 'active')
                                    <span class="badge bg-success-subtle text-success">Đang hoạt động</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Ngừng hoạt động</span>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <label class="text-muted small text-uppercase fw-bold d-block border-top pt-2">Giá Tour</label>
                                <span class="text-primary fw-bold fs-5">{{ number_format($tour->price, 0, ',', '.') }} VND</span>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small text-uppercase fw-bold d-block border-top pt-2">Sức chứa</label>
                                <span class="text-dark"><i class="fas fa-users me-1"></i>{{ $tour->max_people }} người</span>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small text-uppercase fw-bold d-block border-top pt-2">Thời gian</label>
                                <span class="text-dark"><i class="fas fa-clock me-1"></i>{{ $tour->duration ?: '—' }} ngày</span>
                            </div>

                            <div class="col-md-4">
                                <label class="text-muted small text-uppercase fw-bold d-block border-top pt-2">Ngày bắt đầu</label>
                                <span class="text-muted">{{ $tour->start_date ?: '—' }}</span>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small text-uppercase fw-bold d-block border-top pt-2">Ngày kết thúc</label>
                                <span class="text-muted">{{ $tour->end_date ?: '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4 pt-3 border-top">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small text-uppercase fw-bold d-block mb-2">Mô tả chuyến đi</label>
                        <p class="text-dark mb-0 small" style="line-height: 1.6; text-align: justify;">{{ $tour->description ?: 'Chưa có mô tả.' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small text-uppercase fw-bold d-block mb-2 text-warning">Chính sách & Quy định</label>
                        <p class="text-muted mb-0 small" style="line-height: 1.6;">{{ $tour->policy ?: 'Chưa có chính sách.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        @if($tour->itineraries->count())
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-map-signs me-2 text-primary"></i>Lịch trình chi tiết</h5>
                </div>
                <div class="card-body">
                    @foreach($tour->itineraries->sortBy('day_number') as $item)
                        <div class="mb-3 p-3 rounded-3 border-start border-4 border-primary bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark">Ngày {{ $item->day_number }}: {{ $item->title }}</strong>
                                @if($item->time_start)
                                    <span class="badge bg-white text-muted border fw-normal">{{ $item->time_start }} – {{ $item->time_end }}</span>
                                @endif
                            </div>
                            @if($item->location)
                                <div class="small text-primary mb-2"><i class="fas fa-map-marker-alt me-1"></i>{{ $item->location }}</div>
                            @endif
                            <p class="text-muted small mb-0">{{ $item->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-5">
        @if($tour->departureSchedules->count())
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-calendar-alt me-2 text-primary"></i>Lịch khởi hành</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="small text-uppercase">
                                    <th class="ps-3">Ngày đi/về</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tour->departureSchedules as $schedule)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark small">{{ $schedule->start_date }}</div>
                                            <div class="text-muted" style="font-size: 11px;">Đến: {{ $schedule->end_date }}</div>
                                        </td>
                                        <td>
                                            @php
                                                $statusMap = [
                                                    'scheduled' => ['label' => 'Đã lên lịch', 'class' => 'bg-primary-subtle text-primary'],
                                                    'ongoing' => ['label' => 'Đang đi', 'class' => 'bg-warning-subtle text-warning'],
                                                    'completed' => ['label' => 'Xong', 'class' => 'bg-success-subtle text-success'],
                                                    'cancelled' => ['label' => 'Hủy', 'class' => 'bg-danger-subtle text-danger'],
                                                ];
                                                $s = $statusMap[$schedule->status] ?? ['label' => $schedule->status, 'class' => 'bg-light text-muted'];
                                            @endphp
                                            <span class="badge {{ $s['class'] }} px-2 py-1" style="font-size: 11px;">{{ $s['label'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
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
