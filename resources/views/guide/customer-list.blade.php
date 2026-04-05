@extends('guide.layout')

@section('page-title', 'Danh sách khách hàng')
@section('page-sub', 'Danh sách khách hàng đã đặt tour của bạn')

@section('content')

    <style>
        .participant-child {
            margin-left: 20px;
        }

        .search-tour-box {
            margin-bottom: 20px;
        }

        .schedule-group {
            transition: opacity 0.3s ease;
        }

        .schedule-group.hidden {
            display: none;
        }

        .no-results {
            text-align: center;
            padding: 20px;
            color: var(--text-muted);
        }

        .tour-header {
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s ease;
        }

        .tour-header:hover {
            background-color: #f1f5f9 !important;
        }

        .tour-header.collapsed {
            border-bottom: none !important;
        }

        .expand-icon {
            display: inline-block;
            transition: transform 0.3s ease;
            margin-right: 8px;
            font-size: 16px;
        }

        .expand-icon.collapsed {
            transform: rotate(-90deg);
        }

        .tour-content {
            max-height: 1000px;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
            opacity: 1;
        }

        .tour-content.collapsed {
            max-height: 0;
            opacity: 0;
            padding: 0 !important;
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
        <div class="search-tour-box">
            <input type="text" id="tourSearchInput" class="form-control" placeholder="🔍 Tìm kiếm tour theo tên..."
                style="font-size: 14px; padding: 12px 14px;">
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Danh sách khách hàng</div>
                <div style="font-size:13px;color:var(--text-muted)">Tổng cộng {{ $totalPassengers }} khách</div>
            </div>
            <div class="card-body" id="tourScheduleContainer">
                @php
                    $scheduleGroups = ($participants ?? collect())->groupBy('schedule_id');
                @endphp

                @foreach($scheduleGroups as $scheduleId => $scheduleMembers)
                    @php
                        $firstMember = $scheduleMembers->first();
                        $groupedMembers = $scheduleMembers->groupBy('group_key');
                    @endphp
                    <div class="schedule-group" data-tour-name="{{ strtolower($firstMember->tour_name ?? 'N/A') }}"
                        style="border:1px solid var(--border);border-radius:14px;margin-bottom:14px;overflow:hidden;background:#fff;">
                        <div class="tour-header collapsed"
                            style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 14px;background:#f8fafc;border-bottom:1px solid var(--border)">
                            <div style="display:flex;align-items:center;flex:1;cursor:pointer;">
                                <span class="expand-icon collapsed">▼</span>
                                <div>
                                    <div style="font-size:14px;font-weight:700">{{ $firstMember->tour_name ?? 'N/A' }}</div>
                                    <div style="font-size:12px;color:var(--text-muted)">
                                        Lịch #{{ $scheduleId }} · Khởi hành:
                                        {{ \Carbon\Carbon::parse($firstMember->start_date)->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                            <span class="badge badge-info">{{ $scheduleMembers->count() }} khách</span>
                        </div>

                        <div class="tour-content collapsed" style="padding:12px;">
                            @foreach($groupedMembers as $groupMembers)
                                @php $groupLeader = $groupMembers->firstWhere('is_representative', true) ?? $groupMembers->first(); @endphp
                                <div style="border:1px solid #e2e8f0;border-radius:12px;margin-bottom:10px;overflow:hidden;">
                                    <div
                                        style="display:flex;align-items:center;justify-content:space-between;gap:8px;padding:10px 12px;background:#f1f5f9;border-bottom:1px solid #e2e8f0;">
                                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                            <span class="badge badge-info">{{ $groupLeader->group_name }}</span>
                                            <span style="font-size:13px;font-weight:600">
                                                {{ ($groupLeader->group_size ?? 1) > 1 ? 'Đại diện' : 'Khách' }}:
                                                {{ $groupLeader->fullname ?? 'N/A' }}
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
                                                                <span class="badge badge-info">Đại diện nhóm {{ $participant->group_size }}
                                                                    người</span>
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
                                                <div
                                                    style="font-size:12px;color:var(--text-muted);font-family:monospace;white-space:nowrap;">
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
                <div id="noResultsMessage" class="no-results" style="display:none;">
                    Không tìm thấy tour nào khớp với từ khóa tìm kiếm.
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('tourSearchInput');
                const scheduleGroups = document.querySelectorAll('.schedule-group');
                const noResultsMessage = document.getElementById('noResultsMessage');

                // Handle expand/collapse functionality
                scheduleGroups.forEach(function (group) {
                    const header = group.querySelector('.tour-header');
                    const content = group.querySelector('.tour-content');
                    const expandIcon = header.querySelector('.expand-icon');

                    if (header) {
                        header.addEventListener('click', function (e) {
                            // Don't trigger when clicking the badge
                            if (e.target.closest('.badge')) return;

                            const isCollapsed = header.classList.contains('collapsed');

                            if (isCollapsed) {
                                // Expand
                                header.classList.remove('collapsed');
                                content.classList.remove('collapsed');
                                expandIcon.classList.remove('collapsed');
                                header.style.borderBottom = '1px solid var(--border)';
                            } else {
                                // Collapse
                                header.classList.add('collapsed');
                                content.classList.add('collapsed');
                                expandIcon.classList.add('collapsed');
                                header.style.borderBottom = 'none';
                            }
                        });
                    }
                });

                // Handle search functionality
                searchInput.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase().trim();
                    let visibleCount = 0;

                    scheduleGroups.forEach(function (group) {
                        const tourName = group.getAttribute('data-tour-name');

                        if (searchTerm === '' || tourName.includes(searchTerm)) {
                            group.classList.remove('hidden');
                            group.style.display = '';
                            visibleCount++;
                        } else {
                            group.classList.add('hidden');
                            group.style.display = 'none';
                        }
                    });

                    // Show/hide no results message
                    if (visibleCount === 0 && searchTerm !== '') {
                        noResultsMessage.style.display = 'block';
                    } else {
                        noResultsMessage.style.display = 'none';
                    }
                });
            });
        </script>
    @endif

@endsection