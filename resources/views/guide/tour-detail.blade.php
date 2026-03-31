@extends('guide.layout')

@section('title', $schedule->tour->name ?? 'Chi tiết Tour')
@section('page-title', $schedule->tour->name ?? 'Chi tiết Tour')
@section('page-sub', 'Mã lịch khởi hành #' . $schedule->schedule_id)

@section('content')

<style>
    .participant-child {
        margin-left: 20px;
    }
</style>

<a href="{{ route('guide.dashboard') }}" class="back-link">← Quay lại Dashboard</a>

{{-- TOUR INFO --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <div class="card-title">📋 Thông tin chuyến đi</div>
        @if($schedule->start_date > now())
        <span class="badge badge-info">📅 Sắp diễn ra</span>
        @elseif($schedule->end_date < now())
            <span class="badge badge-gray">✅ Đã hoàn thành</span>
            @else
            <span class="badge badge-success">🚀 Đang diễn ra</span>
            @endif
    </div>
    <div class="card-body">
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:20px">
            <div>
                <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Ngày khởi hành</div>
                <div style="font-size:16px;font-weight:600">{{ $schedule->start_date->format('d/m/Y') }}</div>
            </div>
            <div>
                <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Ngày kết thúc</div>
                <div style="font-size:16px;font-weight:600">{{ $schedule->end_date->format('d/m/Y') }}</div>
            </div>
            <div>
                <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Thời gian</div>
                <div style="font-size:16px;font-weight:600">{{ $schedule->start_date->diffInDays($schedule->end_date) + 1 }} ngày</div>
            </div>
            <div>
                <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Số khách</div>
                <div style="font-size:16px;font-weight:600">{{ $totalPassengers }} người</div>
            </div>
            <div>
                <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Điểm tập trung</div>
                <div style="font-size:16px;font-weight:600">{{ $schedule->meeting_point ?? '—' }}</div>
            </div>
            <div>
                <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Giá tour</div>
                <div style="font-size:16px;font-weight:600;color:var(--primary)">
                    {{ $schedule->tour->price ? number_format($schedule->tour->price, 0, ',', '.') . ' ₫' : '—' }}
                </div>
            </div>
            <div>
                <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">Danh mục</div>
                <div style="font-size:16px;font-weight:600">{{ $schedule->tour->category->name ?? '—' }}</div>
            </div>
        </div>

        @if($schedule->tour->description ?? false)
        <div style="margin-top:20px;padding:14px 16px;background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;font-size:14px;color:#0c4a6e">
            📖 {{ $schedule->tour->description }}
        </div>
        @endif

        @if($schedule->notes)
        <div style="margin-top:12px;padding:14px 16px;background:#fefce8;border:1px solid #fde68a;border-radius:8px;font-size:14px;color:#92400e">
            📝 {{ $schedule->notes }}
        </div>
        @endif

        <div style="display:flex;gap:10px;margin-top:20px;flex-wrap:wrap">
            <a href="{{ route('guide.attendance', $schedule->schedule_id) }}" class="btn btn-primary">✅ Điểm danh khách</a>
            <a href="{{ route('guide.itinerary', $schedule->schedule_id) }}" class="btn btn-outline">🗓️ Quản lý lịch trình</a>
        </div>
    </div>
</div>

{{-- CUSTOMER LIST --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <div class="card-title">👥 Danh sách khách hàng</div>
        <span class="badge badge-info">{{ $totalPassengers }} người</span>
    </div>
    <div class="card-body" style="padding-top:12px">
        @if(($participants ?? collect())->count() > 0)
        @php
        $participantGroups = ($participants ?? collect())->groupBy('group_key');
        @endphp

        @foreach($participantGroups as $groupMembers)
        @php
        $groupLeader = $groupMembers->firstWhere('is_representative', true) ?? $groupMembers->first();
        @endphp
        <div style="border:1px solid var(--border);border-radius:12px;margin-bottom:12px;overflow:hidden;background:#fff;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 14px;background:#f1f5f9;border-bottom:1px solid var(--border)">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <span class="badge badge-info">{{ $groupLeader->group_name }}</span>
                    <span style="font-size:13px;font-weight:600">Đại diện: {{ $groupLeader->fullname ?? '—' }}</span>
                </div>
                <span class="badge badge-gray">{{ $groupMembers->count() }} người</span>
            </div>

            <div style="padding:10px;">
                @foreach($groupMembers as $participant)
                <div class="{{ !$participant->is_representative ? 'participant-child' : '' }}"
                    style="display:flex;align-items:center;justify-content:space-between;gap:16px;padding:12px;border-radius:10px;border:1px solid #e2e8f0;background:#f8fafc;margin-bottom:8px;">
                    <div style="display:flex;align-items:center;gap:14px;flex:1;min-width:0">
                        <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px;flex-shrink:0">
                            {{ strtoupper(substr($participant->fullname ?? 'K', 0, 1)) }}
                        </div>
                        <div style="min-width:0">
                            <div style="font-weight:600;font-size:14px">{{ $participant->fullname ?? '—' }}</div>
                            <div style="font-size:12px;color:var(--text-muted);margin-top:2px">
                                {{ $participant->phone ?? '' }}
                                @if($participant->phone && $participant->email) · @endif
                                {{ $participant->email ?? '' }}
                            </div>
                            <div style="margin-top:6px">
                                @if($participant->is_representative)
                                <span class="badge badge-info">Đại diện nhóm {{ $participant->group_size }} người</span>
                                @else
                                <span class="badge badge-gray">Người đi cùng</span>
                                @endif

                                @if($participant->booking_status == 'upcoming')
                                <span class="badge badge-info">Sắp tới</span>
                                @elseif($participant->booking_status == 'ongoing')
                                <span class="badge badge-success">Đang đi</span>
                                @elseif($participant->booking_status == 'completed')
                                <span class="badge badge-gray">Hoàn thành</span>
                                @elseif($participant->booking_status == 'cancelled')
                                <span class="badge badge-danger">Đã hủy</span>
                                @elseif(!$participant->booking_status)
                                <span class="badge badge-gray">Theo nhóm đặt</span>
                                @else
                                <span class="badge badge-gray">{{ $participant->booking_status }}</span>
                                @endif

                                @if($participant->payment_status == 'paid')
                                <span class="badge badge-success">Đã thanh toán</span>
                                @elseif($participant->payment_status == 'deposit')
                                <span class="badge badge-warning">Đặt cọc</span>
                                @elseif(!$participant->payment_status)
                                <span class="badge badge-gray">Theo nhóm đặt</span>
                                @else
                                <span class="badge badge-danger">Chưa thanh toán</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div style="font-family:monospace;font-size:12px;color:var(--text-muted);white-space:nowrap">
                        CCCD: {{ $participant->id_number ?? '—' }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        @else
        <div class="empty-state">
            <div class="empty-icon">👤</div>
            <div class="empty-text">Chưa có khách hàng đăng ký hoặc chưa có dữ liệu người đi cùng.</div>
        </div>
        @endif
    </div>
</div>

{{-- ITINERARY PREVIEW --}}
@if($itineraries->count() > 0)
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <div class="card-title">🗓️ Lịch trình</div>
        <a href="{{ route('guide.itinerary', $schedule->schedule_id) }}" class="btn btn-outline btn-sm">Quản lý →</a>
    </div>
    <div class="card-body">
        @foreach($itineraries as $item)
        <div style="display:flex;gap:16px;padding:14px 0;border-bottom:1px solid var(--border)">
            <div style="background:var(--primary-bg);color:var(--primary);padding:8px 14px;border-radius:8px;font-weight:700;font-size:13px;white-space:nowrap;height:fit-content">
                Ngày {{ $item->day_number }}
            </div>
            <div>
                <div style="font-weight:600;margin-bottom:4px">{{ $item->title }}</div>
                <div style="font-size:13px;color:var(--text-muted)">{{ $item->description }}</div>
                @if($item->location)
                <div style="font-size:12px;color:var(--primary);margin-top:4px">📍 {{ $item->location }}</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- FEEDBACK --}}
@if($feedbacks->count() > 0)
<div class="card">
    <div class="card-header">
        <div class="card-title">💬 Đánh giá từ khách</div>
        <span class="badge badge-warning">{{ $feedbacks->count() }} đánh giá</span>
    </div>
    <div class="card-body">
        @foreach($feedbacks as $fb)
        <div style="padding:16px;background:#f8fafc;border-radius:10px;margin-bottom:12px;border:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px">
                <div>
                    @if($fb->rating)
                    <div style="color:#f59e0b;font-size:16px;letter-spacing:2px">
                        @for($i=1;$i<=5;$i++){{ $i<=$fb->rating ? '★' : '☆' }}@endfor
                            <span style="font-size:13px;color:var(--text-muted);margin-left:6px">{{ $fb->rating }}/5</span>
                    </div>
                    @endif
                    @if($fb->type == 'su_co')
                    <span class="badge badge-danger" style="margin-top:4px">⚠️ Sự cố</span>
                    @else
                    <span class="badge badge-success" style="margin-top:4px">✅ Đánh giá</span>
                    @endif
                </div>
                <div style="font-size:12px;color:var(--text-muted)">{{ $fb->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div style="font-size:14px;color:var(--text)">{{ $fb->content }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection