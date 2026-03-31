@extends('guide.layout')

@section('title', 'Điểm danh')
@section('page-title', 'Điểm danh khách hàng')
@section('page-sub', ($schedule->tour->name ?? 'N/A') . ' — ' . $schedule->start_date->format('d/m/Y'))

@section('content')

<style>
    .participant-child {
        margin-left: 20px;
    }
</style>

<a href="{{ route('guide.tour.detail', $schedule->schedule_id) }}" class="back-link">← Quay lại Chi tiết Tour</a>

{{-- STATS --}}
@php
$present = ($participants ?? collect())->filter(function ($participant) use ($latestAttendanceByParticipant) {
return optional($latestAttendanceByParticipant->get($participant->tour_customer_id))->status === 'present';
})->count();
$absent = ($participants ?? collect())->filter(function ($participant) use ($latestAttendanceByParticipant) {
return optional($latestAttendanceByParticipant->get($participant->tour_customer_id))->status === 'absent';
})->count();
$total = ($participants ?? collect())->count() > 0 ? ($participants ?? collect())->count() : $totalPassengers;
@endphp
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon blue">👥</div>
        <div>
            <div class="stat-value">{{ $total }}</div>
            <div class="stat-label">Tổng khách</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div>
            <div class="stat-value">{{ $present }}</div>
            <div class="stat-label">Có mặt</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">❌</div>
        <div>
            <div class="stat-value">{{ $absent }}</div>
            <div class="stat-label">Vắng mặt</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">❓</div>
        <div>
            <div class="stat-value">{{ $total - $present - $absent }}</div>
            <div class="stat-label">Chưa điểm danh</div>
        </div>
    </div>
</div>

{{-- CUSTOMER LIST --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">👥 Danh sách khách theo nhóm</div>
    </div>
    <div class="card-body" style="padding-top:12px">
        @php
        $participantGroups = ($participants ?? collect())->groupBy('group_key');
        @endphp

        @forelse($participantGroups as $groupMembers)
        @php
        $groupLeader = $groupMembers->firstWhere('is_representative', true) ?? $groupMembers->first();
        @endphp
        <div style="border:1px solid var(--border);border-radius:12px;margin-bottom:12px;overflow:hidden;background:#fff;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 14px;background:#f1f5f9;border-bottom:1px solid var(--border)">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <span class="badge badge-info">{{ $groupLeader->group_name }}</span>
                    <span style="font-size:13px;font-weight:600">
                        {{ ($groupLeader->group_size ?? 1) > 1 ? 'Đại diện' : 'Khách' }}: {{ $groupLeader->fullname ?? '—' }}
                    </span>
                </div>
                <span class="badge badge-gray">{{ $groupMembers->count() }} người</span>
            </div>

            <div style="padding:10px;">
                @foreach($groupMembers as $participant)
                @php
                $latest = $latestAttendanceByParticipant->get($participant->tour_customer_id);
                $defaultBg = $participant->is_representative ? '#f8fafc' : '#fcfcfd';
                @endphp
                <div class="{{ !$participant->is_representative ? 'participant-child' : '' }}"
                    style="display:flex;align-items:center;justify-content:space-between;gap:16px;padding:12px;border-radius:10px;border:1px solid #e2e8f0;background:#f8fafc;margin-bottom:8px;"
                    data-default-bg="{{ $defaultBg }}"
                    onmouseover="this.style.borderColor='#10b981';this.style.background='#ecfdf5'"
                    onmouseout="restoreParticipantRow(this)">
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
                                @if(($participant->group_size ?? 1) > 1)
                                <span class="badge badge-info">Đại diện nhóm</span>
                                @else
                                <span class="badge badge-info">Khách đặt tour</span>
                                @endif
                                @else
                                <span class="badge badge-gray">Người đi cùng</span>
                                @endif

                                @if($latest)
                                @if($latest->status == 'present')
                                <span class="badge badge-success">✓ Có mặt</span>
                                @elseif($latest->status == 'absent')
                                <span class="badge badge-danger">✗ Vắng mặt</span>
                                @else
                                <span class="badge badge-warning">? Chưa rõ</span>
                                @endif
                                <span style="font-size:11px;color:var(--text-muted);margin-left:6px">{{ $latest->marked_at->format('H:i d/m') }}</span>
                                @else
                                <span class="badge badge-gray">Chưa điểm danh</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sm"
                        data-tour-customer-id="{{ $participant->tour_customer_id }}"
                        data-participant-name="{{ e($participant->fullname ?? '') }}"
                        onclick="openModal(this)">
                        ✏️ Điểm danh
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">👤</div>
            <div class="empty-text">Chưa có khách hàng.</div>
        </div>
        @endforelse
    </div>
</div>

{{-- HISTORY --}}
@if($attendances->count() > 0)
<div class="card" style="margin-top:20px">
    <div class="card-header">
        <div class="card-title">📋 Lịch sử điểm danh</div>
        <span class="badge badge-gray">{{ $attendances->count() }} lần</span>
    </div>
    <div class="card-body" style="padding-top:12px">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Khách hàng</th>
                        <th>Trạng thái</th>
                        <th>Ghi chú</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $att)
                    <tr>
                        <td style="font-weight:600">{{ $att->customer->fullname ?? '—' }}</td>
                        <td>
                            @if($att->status == 'present')
                            <span class="badge badge-success">✓ Có mặt</span>
                            @elseif($att->status == 'absent')
                            <span class="badge badge-danger">✗ Vắng mặt</span>
                            @else
                            <span class="badge badge-warning">? Chưa rõ</span>
                            @endif
                        </td>
                        <td style="color:var(--text-muted);font-size:13px">{{ $att->note ?? '—' }}</td>
                        <td style="color:var(--text-muted);font-size:13px">{{ $att->marked_at->format('H:i d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- MODAL --}}
<div class="modal-overlay" id="modal">
    <div class="modal-box">
        <div class="modal-title">✏️ Điểm danh khách hàng</div>
        <p id="modalName" style="color:var(--text-muted);font-size:14px;margin-bottom:20px"></p>

        <form method="POST" action="{{ route('guide.attendance.mark', $schedule->schedule_id) }}">
            @csrf
            <input type="hidden" name="tour_customer_id" id="tourCustomerId">

            <div class="form-group">
                <label class="form-label">Trạng thái</label>
                <div style="display:flex;gap:10px;flex-wrap:wrap">
                    <label style="flex:1;min-width:100px">
                        <input type="radio" name="status" value="present" checked style="display:none" id="r-present">
                        <div class="status-btn" data-for="r-present" onclick="selectStatus(this,'present')"
                            style="padding:12px;border-radius:10px;border:2px solid #10b981;background:#d1fae5;color:#065f46;text-align:center;cursor:pointer;font-weight:600;font-size:14px">
                            ✓ Có mặt
                        </div>
                    </label>
                    <label style="flex:1;min-width:100px">
                        <input type="radio" name="status" value="absent" style="display:none" id="r-absent">
                        <div class="status-btn" data-for="r-absent" onclick="selectStatus(this,'absent')"
                            style="padding:12px;border-radius:10px;border:2px solid var(--border);background:#f8fafc;color:var(--text-muted);text-align:center;cursor:pointer;font-weight:600;font-size:14px">
                            ✗ Vắng mặt
                        </div>
                    </label>
                    <label style="flex:1;min-width:100px">
                        <input type="radio" name="status" value="unknown" style="display:none" id="r-unknown">
                        <div class="status-btn" data-for="r-unknown" onclick="selectStatus(this,'unknown')"
                            style="padding:12px;border-radius:10px;border:2px solid var(--border);background:#f8fafc;color:var(--text-muted);text-align:center;cursor:pointer;font-weight:600;font-size:14px">
                            ? Chưa rõ
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="note">Ghi chú (tùy chọn)</label>
                <textarea class="form-control" name="note" id="note" placeholder="Nhập ghi chú nếu cần..."></textarea>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Hủy</button>
                <button type="submit" class="btn btn-primary">Xác nhận điểm danh</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal(buttonEl) {
        const id = buttonEl.getAttribute('data-tour-customer-id') || '';
        const name = buttonEl.getAttribute('data-participant-name') || '';

        document.getElementById('tourCustomerId').value = id;
        document.getElementById('modalName').textContent = 'Khách hàng: ' + name;
        document.getElementById('modal').classList.add('active');
        // reset
        selectStatus(document.querySelector('[data-for="r-present"]'), 'present');
        document.getElementById('note').value = '';
    }

    function closeModal() {
        document.getElementById('modal').classList.remove('active');
    }

    function restoreParticipantRow(el) {
        el.style.borderColor = '#e2e8f0';
        el.style.background = el.dataset.defaultBg || '#f8fafc';
    }

    function selectStatus(el, val) {
        document.querySelectorAll('.status-btn').forEach(b => {
            b.style.borderColor = 'var(--border)';
            b.style.background = '#f8fafc';
            b.style.color = 'var(--text-muted)';
        });
        if (val === 'present') {
            el.style.borderColor = '#10b981';
            el.style.background = '#d1fae5';
            el.style.color = '#065f46';
        }
        if (val === 'absent') {
            el.style.borderColor = '#ef4444';
            el.style.background = '#fee2e2';
            el.style.color = '#991b1b';
        }
        if (val === 'unknown') {
            el.style.borderColor = '#f59e0b';
            el.style.background = '#fef3c7';
            el.style.color = '#92400e';
        }
        document.getElementById('r-' + val).checked = true;
    }

    document.getElementById('modal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endpush