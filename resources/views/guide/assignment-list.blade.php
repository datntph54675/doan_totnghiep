@extends('guide.layout')

@section('title', 'Danh sách tour được gán')
@section('page-title', 'Danh sách tour được gán')
@section('page-sub', 'Xác nhận hoặc từ chối các tour được gán cho bạn')

@section('content')
<div style="max-width: 1000px;">
    <!-- Pending Assignments -->
    @if($assignments->where('status', 'pending')->count() > 0)
    <div class="card" style="margin-bottom: 24px;">
        <div style="padding: 20px; border-bottom: 1px solid var(--border);">
            <h3 style="font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                ⏳ Tour chờ xác nhận
                <span class="badge badge-warning">{{ $assignments->where('status', 'pending')->count() }}</span>
            </h3>
        </div>

        <div style="padding: 20px;">
            @foreach($assignments->where('status', 'pending') as $assignment)
            <div class="card" style="margin-bottom: 16px; border: 1px solid var(--border);">
                <div style="padding: 16px;">
                    <div style="display: grid; grid-template-columns: 1fr auto; gap: 16px; margin-bottom: 12px;">
                        <div>
                            <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 4px;">
                                {{ $assignment->schedule->tour->tour_name ?? $assignment->schedule->tour->name }}
                            </h4>
                            <p style="font-size: 13px; color: var(--text-muted);">
                                📅 {{ $assignment->schedule->start_date->format('d/m/Y') }} - {{ $assignment->schedule->end_date->format('d/m/Y') }}
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span class="badge badge-warning">Chờ xác nhận</span>
                            <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                                Được gán: {{ $assignment->assigned_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    @if($assignment->note)
                    <div style="background: var(--bg); padding: 12px; border-radius: 8px; margin-bottom: 12px;">
                        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">📝 Ghi chú:</p>
                        <p style="font-size: 14px;">{{ $assignment->note }}</p>
                    </div>
                    @endif

                    <div style="display: flex; gap: 12px;">
                        <form method="POST" action="{{ route('guide.assignments.accept', $assignment->id) }}" style="flex: 1;">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                ✅ Xác nhận nhận tour
                            </button>
                        </form>
                        <button
                            type="button"
                            class="btn btn-secondary js-open-reject-modal"
                            style="flex: 0 0 auto;"
                            data-assignment-id="{{ $assignment->id }}"
                            data-reason="">
                            ❌ Từ chối
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Rejected Assignments (editable) -->
    @if($assignments->where('status', 'rejected')->count() > 0)
    <div class="card" style="margin-bottom: 24px;">
        <div style="padding: 20px; border-bottom: 1px solid var(--border);">
            <h3 style="font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                ❌ Tour đã từ chối
                <span class="badge badge-danger">{{ $assignments->where('status', 'rejected')->count() }}</span>
            </h3>
        </div>

        <div style="padding: 20px;">
            @foreach($assignments->where('status', 'rejected') as $assignment)
            <div class="card" style="margin-bottom: 16px; border: 1px solid var(--border); border-left: 3px solid var(--danger);">
                <div style="padding: 16px;">
                    <div style="display: grid; grid-template-columns: 1fr auto; gap: 16px; margin-bottom: 12px;">
                        <div>
                            <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 4px;">
                                {{ $assignment->schedule->tour->tour_name ?? $assignment->schedule->tour->name }}
                            </h4>
                            <p style="font-size: 13px; color: var(--text-muted);">
                                📅 {{ $assignment->schedule->start_date->format('d/m/Y') }} - {{ $assignment->schedule->end_date->format('d/m/Y') }}
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span class="badge badge-danger">Từ chối</span>
                            <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                                Từ chối: {{ $assignment->confirmed_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    @if($assignment->rejection_reason)
                    <div style="background: #fee2e2; padding: 12px; border-radius: 8px; margin-bottom: 12px; border-left: 3px solid var(--danger);">
                        <p style="font-size: 13px; color: #991b1b; margin-bottom: 4px; font-weight: 500;">📝 Lý do từ chối:</p>
                        <p style="font-size: 14px; color: #991b1b;">{{ $assignment->rejection_reason }}</p>
                    </div>
                    @endif

                    @if($assignment->note)
                    <div style="background: var(--bg); padding: 12px; border-radius: 8px; margin-bottom: 12px;">
                        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">📝 Ghi chú từ admin:</p>
                        <p style="font-size: 14px;">{{ $assignment->note }}</p>
                    </div>
                    @endif

                    <div style="display: flex; gap: 12px;">
                        <form method="POST" action="{{ route('guide.assignments.accept', $assignment->id) }}" style="flex: 1;">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                ✅ Đồng ý nhận tour
                            </button>
                        </form>
                        <button
                            type="button"
                            class="btn btn-secondary js-open-reject-modal"
                            style="flex: 0 0 auto;"
                            data-assignment-id="{{ $assignment->id }}"
                            data-reason="{{ $assignment->rejection_reason }}">
                            ✏️ Cập nhật lý do
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Empty State -->
    @if($assignments->isEmpty())
    <div class="card" style="text-align: center; padding: 40px;">
        <p style="font-size: 40px; margin-bottom: 12px;">✨</p>
        <p style="font-size: 16px; font-weight: 600; margin-bottom: 4px;">Không có tour nào chờ xác nhận</p>
        <p style="color: var(--text-muted);">Tất cả tour được gán cho bạn đã được xác nhận hoặc từ chối</p>
    </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="font-size: 18px; font-weight: 600;">Từ chối nhận tour</h2>
            <button type="button" id="closeRejectModalBtn" style="background: none; border: none; font-size: 24px; cursor: pointer;">×</button>
        </div>

        <form id="rejectForm" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px;">
                    Lý do từ chối tour <span style="color: var(--danger);">*</span>
                </label>
                <textarea name="rejection_reason" id="rejectionReason" required
                    style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 14px; resize: vertical; min-height: 100px;"
                    placeholder="Vui lòng nhập lý do chi tiết (ít nhất 5 ký tự)"></textarea>
                @error('rejection_reason')
                <p style="color: var(--danger); font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-danger" style="flex: 1;">Xác nhận từ chối</button>
                <button type="button" id="cancelRejectModalBtn" class="btn btn-secondary" style="flex: 1;">Hủy</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        position: relative;
        background: var(--card);
        border-radius: var(--radius);
        padding: 24px;
        max-width: 500px;
        box-shadow: var(--shadow-lg);
        z-index: 1001;
    }

    .btn-secondary {
        background: var(--border);
        color: var(--text);
    }

    .btn-secondary:hover {
        background: #cbd5e1;
    }

    .btn-danger {
        background: var(--danger);
        color: #fff;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    textarea:focus {
        outline: none !important;
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
</style>

<script>
    let currentAssignmentId = null;

    function openRejectModal(assignmentId, currentReason = '') {
        currentAssignmentId = assignmentId;
        document.getElementById('rejectionReason').value = currentReason;
        document.getElementById('rejectForm').action = `/guide/assignments/${assignmentId}/reject`;
        document.getElementById('rejectModal').style.display = 'flex';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
        currentAssignmentId = null;
    }

    document.querySelectorAll('.js-open-reject-modal').forEach(function(button) {
        button.addEventListener('click', function() {
            const assignmentId = this.getAttribute('data-assignment-id');
            const reason = this.getAttribute('data-reason') || '';
            openRejectModal(assignmentId, reason);
        });
    });

    document.getElementById('closeRejectModalBtn')?.addEventListener('click', closeRejectModal);
    document.getElementById('cancelRejectModalBtn')?.addEventListener('click', closeRejectModal);

    // Close modal when clicking outside
    document.getElementById('rejectModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });
</script>
@endsection