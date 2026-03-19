@extends('guide.layout')

@section('title', 'Lịch trình')
@section('page-title', 'Quản lý lịch trình')
@section('page-sub', ($schedule->tour->name ?? 'N/A') . ' — ' . $schedule->start_date->format('d/m/Y'))

@section('content')

<a href="{{ route('guide.tour.detail', $schedule->schedule_id) }}" class="back-link">← Quay lại Chi tiết Tour</a>

<div style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1px solid #a7f3d0;border-radius:12px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;gap:12px">
    <span style="font-size:24px">💡</span>
    <div style="font-size:14px;color:#065f46">
        Bạn có thể cập nhật thông tin lịch trình để phù hợp với tình hình thực tế. Nhấn <strong>Cập nhật</strong> trên từng ngày để chỉnh sửa.
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">🗓️ Lịch trình chi tiết</div>
        <span class="badge badge-info">{{ $itineraries->count() }} ngày</span>
    </div>
    <div class="card-body">
        @forelse($itineraries as $item)
        <div style="display:flex;gap:0;margin-bottom:0">
            {{-- Timeline line --}}
            <div style="display:flex;flex-direction:column;align-items:center;margin-right:20px">
                <div style="width:40px;height:40px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0;z-index:1">
                    {{ $item->day_number }}
                </div>
                @if(!$loop->last)
                <div style="width:2px;flex:1;background:var(--border);min-height:24px;margin:4px 0"></div>
                @endif
            </div>

            {{-- Content --}}
            <div style="flex:1;padding-bottom:{{ $loop->last ? '0' : '24px' }}">
                <div style="background:#f8fafc;border:1px solid var(--border);border-radius:12px;padding:18px;transition:all .2s"
                     onmouseover="this.style.borderColor='#10b981';this.style.background='#ecfdf5'"
                     onmouseout="this.style.borderColor='var(--border)';this.style.background='#f8fafc'">
                    <div style="display:flex;justify-content:space-between;align-items:start;gap:12px">
                        <div style="flex:1">
                            <div style="font-size:16px;font-weight:700;margin-bottom:6px">{{ $item->title }}</div>
                            <div style="font-size:14px;color:var(--text-muted);line-height:1.6;margin-bottom:8px">{{ $item->description }}</div>
                            <div style="display:flex;gap:12px;flex-wrap:wrap;font-size:12px">
                                @if($item->location)
                                <span style="color:var(--primary);font-weight:500">📍 {{ $item->location }}</span>
                                @endif
                                @if($item->time_start)
                                <span style="color:var(--text-muted)">🕐 {{ $item->time_start }}</span>
                                @endif
                            </div>
                        </div>
                        <button class="btn btn-outline btn-sm" style="flex-shrink:0"
                            onclick="openEdit({{ $item->itinerary_id }}, '{{ addslashes($item->title) }}', '{{ addslashes($item->description) }}', '{{ addslashes($item->location ?? '') }}')">
                            ✏️ Cập nhật
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <div class="empty-text">Chưa có lịch trình chi tiết cho tour này.</div>
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <div class="modal-title">✏️ Cập nhật lịch trình</div>

        <form method="POST" id="editForm">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Tiêu đề</label>
                <input type="text" name="title" id="editTitle" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả chi tiết</label>
                <textarea name="description" id="editDesc" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Địa điểm</label>
                <input type="text" name="location" id="editLocation" class="form-control" placeholder="VD: Hạ Long, Quảng Ninh">
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeEdit()">Hủy</button>
                <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openEdit(id, title, desc, location) {
    document.getElementById('editTitle').value = title;
    document.getElementById('editDesc').value = desc;
    document.getElementById('editLocation').value = location;
    document.getElementById('editForm').action = '/guide/itinerary/' + id;
    document.getElementById('editModal').classList.add('active');
}

function closeEdit() {
    document.getElementById('editModal').classList.remove('active');
}

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEdit();
});
</script>
@endpush
