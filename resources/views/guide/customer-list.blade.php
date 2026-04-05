@extends('guide.layout')

@section('page-title', 'Danh sách khách hàng')
@section('page-sub', 'Danh sách khách hàng đã đặt tour của bạn')

@section('content')

<style>
    .participant-child {
        margin-left: 20px;
    }
</style>

@if(($participants ?? collect())->isEmpty())
<div class="card">
    <div class="card-body">
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <div class="empty-text">Hiện tại không có khách hàng nào đặt tour của bạn.</div>
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-header">
        <div class="card-title">Danh sách khách hàng theo nhóm</div>
        <div style="font-size:13px;color:var(--text-muted)">Tổng cộng {{ $totalPassengers }} khách</div>
    </div>
    <div class="card-body">
        @php
        $scheduleGroups = ($participants ?? collect())->groupBy('schedule_id');
        @endphp

        @foreach($scheduleGroups as $scheduleId => $scheduleMembers)
        @php
        $firstMember = $scheduleMembers->first();
        $groupedMembers = $scheduleMembers->groupBy('group_key');
        @endphp
        <div style="border:1px solid var(--border);border-radius:14px;margin-bottom:14px;overflow:hidden;background:#fff;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 14px;background:#f8fafc;border-bottom:1px solid var(--border)">
                <div>
                    <div style="font-size:14px;font-weight:700">{{ $firstMember->tour_name ?? 'N/A' }}</div>
                    <div style="font-size:12px;color:var(--text-muted)">
                        Lịch #{{ $scheduleId }} · Khởi hành: {{ \Carbon\Carbon::parse($firstMember->start_date)->format('d/m/Y') }}
                    </div>
                </div>
                <span class="badge badge-info">{{ $scheduleMembers->count() }} khách</span>
            </div>

            <div style="padding:12px;">
                @foreach($groupedMembers as $groupMembers)
                @php $groupLeader = $groupMembers->firstWhere('is_representative', true) ?? $groupMembers->first(); @endphp
                <div style="border:1px solid #e2e8f0;border-radius:12px;margin-bottom:10px;overflow:hidden;">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;padding:10px 12px;background:#f1f5f9;border-bottom:1px solid #e2e8f0;">
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                            <span class="badge badge-info">{{ $groupLeader->group_name }}</span>
                            <span style="font-size:13px;font-weight:600">
                                {{ ($groupLeader->group_size ?? 1) > 1 ? 'Đại diện' : 'Khách' }}: {{ $groupLeader->fullname ?? 'N/A' }}
                            </span>
                        </div>
                        <span class="badge badge-gray">{{ $groupMembers->count() }} người</span>
                    </div>

                    <div style="padding:8px;">
                        @foreach($groupMembers as $participant)
                        <div class="{{ !$participant->is_representative ? 'participant-child' : '' }}"
                            style="display:flex;align-items:center;justify-content:space-between;gap:14px;padding:10px 12px;border:1px solid #e2e8f0;border-radius:10px;background:#f8fafc;margin-bottom:8px;">
                            <div style="min-width:0;">
                                <div style="font-size:14px;font-weight:600;">{{ $participant->fullname ?? 'N/A' }}</div>
                                <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                                    {{ $participant->phone ?? 'N/A' }}
                                    @if(($participant->phone ?? null) && ($participant->email ?? null)) · @endif
                                    {{ $participant->email ?? 'N/A' }}
                                </div>
                                <div style="margin-top:6px;">
                                    @if($participant->is_representative)
                                    @if(($participant->group_size ?? 1) > 1)
                                    <span class="badge badge-info">Đại diện nhóm {{ $participant->group_size }} người</span>
                                    @else
                                    <span class="badge badge-info">Khách đặt tour</span>
                                    @endif
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
                                </div>
                            </div>
                            <div style="font-size:12px;color:var(--text-muted);font-family:monospace;white-space:nowrap;">
                                CCCD: {{ $participant->id_number ?? 'N/A' }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection