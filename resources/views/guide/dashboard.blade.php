@extends('guide.layout')

@section('page-title', 'Dashboard')
@section('page-sub', 'Chào mừng trở lại, ' . (auth()->user()->fullname ?? auth()->user()->username))

@section('content')

@if(!$guide)
<div class="card">
    <div class="card-body">
        <div class="empty-state">
            <div class="empty-icon">⚠️</div>
            <div class="empty-text">Tài khoản chưa được liên kết với hồ sơ hướng dẫn viên. Vui lòng liên hệ admin.</div>
        </div>
    </div>
</div>
@else

{{-- STATS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">📅</div>
        <div>
            <div class="stat-value">{{ $upcomingTours->count() }}</div>
            <div class="stat-label">Tour sắp tới</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">🚀</div>
        <div>
            <div class="stat-value">{{ $ongoingTours->count() }}</div>
            <div class="stat-label">Đang diễn ra</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">✅</div>
        <div>
            <div class="stat-value">{{ $completedTours->count() }}</div>
            <div class="stat-label">Đã hoàn thành</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">👥</div>
        <div>
            <div class="stat-value">{{ $upcomingTours->sum(fn($s) => $s->bookings->count()) + $ongoingTours->sum(fn($s) => $s->bookings->count()) }}</div>
            <div class="stat-label">Tổng khách hiện tại</div>
        </div>
    </div>
</div>

{{-- ONGOING --}}
@if($ongoingTours->count() > 0)
<div class="card" style="margin-bottom:20px" id="ongoing">
    <div class="card-header">
        <div class="card-title">🚀 Đang diễn ra</div>
        <span class="badge badge-success">{{ $ongoingTours->count() }} tour</span>
    </div>
    <div class="card-body">
        @foreach($ongoingTours as $schedule)
        <div class="tour-item">
            <div class="tour-item-left">
                <div class="tour-thumb">🗺️</div>
                <div style="min-width:0">
                    <div class="tour-name">{{ $schedule->tour->name ?? 'N/A' }}</div>
                    <div class="tour-meta">
                        <span>📅 {{ $schedule->start_date->format('d/m/Y') }} → {{ $schedule->end_date->format('d/m/Y') }}</span>
                        <span>👥 {{ $schedule->bookings->count() }} khách</span>
                        @if($schedule->meeting_point)
                        <span>📍 {{ $schedule->meeting_point }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="tour-item-right">
                <span class="badge badge-success">● Đang diễn ra</span>
                <a href="{{ route('guide.tour.detail', $schedule->schedule_id) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- UPCOMING --}}
@if($upcomingTours->count() > 0)
<div class="card" style="margin-bottom:20px" id="upcoming">
    <div class="card-header">
        <div class="card-title">📅 Tour sắp tới</div>
        <span class="badge badge-info">{{ $upcomingTours->count() }} tour</span>
    </div>
    <div class="card-body">
        @foreach($upcomingTours as $schedule)
        <div class="tour-item">
            <div class="tour-item-left">
                <div class="tour-thumb">🏔️</div>
                <div style="min-width:0">
                    <div class="tour-name">{{ $schedule->tour->name ?? 'N/A' }}</div>
                    <div class="tour-meta">
                        <span>📅 {{ $schedule->start_date->format('d/m/Y') }} → {{ $schedule->end_date->format('d/m/Y') }}</span>
                        <span>👥 {{ $schedule->bookings->count() }} khách</span>
                        @if($schedule->meeting_point)
                        <span>📍 {{ $schedule->meeting_point }}</span>
                        @endif
                        <span>⏳ Còn {{ now()->diffInDays($schedule->start_date) }} ngày</span>
                    </div>
                </div>
            </div>
            <div class="tour-item-right">
                <span class="badge badge-info">Sắp tới</span>
                <a href="{{ route('guide.tour.detail', $schedule->schedule_id) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- COMPLETED --}}
@if($completedTours->count() > 0)
<div class="card" id="completed">
    <div class="card-header">
        <div class="card-title">✅ Đã hoàn thành</div>
        <span class="badge badge-gray">5 gần nhất</span>
    </div>
    <div class="card-body">
        @foreach($completedTours as $schedule)
        <div class="tour-item">
            <div class="tour-item-left">
                <div class="tour-thumb" style="background:linear-gradient(135deg,#94a3b8,#64748b)">🏁</div>
                <div style="min-width:0">
                    <div class="tour-name">{{ $schedule->tour->name ?? 'N/A' }}</div>
                    <div class="tour-meta">
                        <span>📅 {{ $schedule->start_date->format('d/m/Y') }} → {{ $schedule->end_date->format('d/m/Y') }}</span>
                        <span>👥 {{ $schedule->bookings->count() }} khách</span>
                    </div>
                </div>
            </div>
            <div class="tour-item-right">
                <span class="badge badge-gray">Hoàn thành</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($upcomingTours->count() == 0 && $ongoingTours->count() == 0 && $completedTours->count() == 0)
<div class="card">
    <div class="card-body">
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <div class="empty-text">Bạn chưa được phân công tour nào.</div>
        </div>
    </div>
</div>
@endif

@endif
@endsection
