@extends('layouts.app')

@section('title', 'Chi Tiết Phản Hồi Từ Hướng Dẫn Viên')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.guide-feedback.index') }}" class="btn btn-outline-secondary mb-3">
            ← Quay Lại
        </a>
        <h2 class="fw-bold text-dark m-0">{{ $feedback->title }}</h2>
        <p class="text-muted small mb-0">
            @if ($feedback->type === 'su_co')
                ⚠️ Báo cáo sự cố
            @else
                🎯 Đánh giá hệ thống
            @endif
        </p>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-3">
            <!-- Feedback Details -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">Hướng Dẫn Viên</p>
                            <p class="fw-bold">{{ $feedback->guide->user->fullname ?? $feedback->guide->user->username }}
                            </p>
                            <small class="text-muted">{{ $feedback->guide->user->email }}</small>
                        </div>
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">Ngày Gửi</p>
                            <p class="fw-bold">{{ $feedback->created_at->format('d/m/Y H:i') }}</p>
                            <small class="text-muted">{{ $feedback->created_at->diffForHumans() }}</small>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3">Nội Dung Phản Hồi</h5>
                    <div class="alert alert-info border-0" style="background: #ecf0ff;">
                        {!! nl2br(e($feedback->content)) !!}
                    </div>
                </div>
            </div>

            <!-- Admin Reply Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-bold m-0">💬 Phản Hồi Của Admin</h5>
                </div>
                <div class="card-body">
                    @if ($feedback->admin_reply)
                        <div class="alert alert-warning border-0 mb-3" style="background: #fffaeb;">
                            <p class="m-0" style="white-space: pre-wrap; word-wrap: break-word;">
                                {!! nl2br(e($feedback->admin_reply)) !!}
                            </p>
                        </div>
                        <p class="small text-muted mb-3">
                            Cập nhật lần cuối: {{ $feedback->updated_at->format('d/m/Y H:i') }}
                        </p>
                    @else
                        <p class="text-muted mb-3">Chưa có phản hồi từ admin</p>
                    @endif

                    <form method="POST" action="{{ route('admin.guide-feedback.updateStatus', $feedback->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="admin_reply" class="form-label small fw-bold">Nhập Phản Hồi (Tùy Chọn)</label>
                            <textarea name="admin_reply" id="admin_reply" class="form-control" rows="6"
                                placeholder="Ghi chú từ admin cho hướng dẫn viên...">{{ $feedback->admin_reply }}</textarea>
                            <small class="text-muted">
                                <span id="char-count">0</span> / 5000 ký tự
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label small fw-bold">Trạng Thái</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" @selected($feedback->status === 'pending')>⏳ Chưa xem</option>
                                <option value="viewed" @selected($feedback->status === 'viewed')>👀 Đã xem</option>
                                <option value="resolved" @selected($feedback->status === 'resolved')>✅ Đã xử lý</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                💾 Lưu Phản Hồi
                            </button>
                            <a href="{{ route('admin.guide-feedback.index') }}" class="btn btn-outline-secondary">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-bold m-0">Thông Tin</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="small text-muted mb-2">Loại Phản Hồi</p>
                        @if ($feedback->type === 'su_co')
                            <span class="badge bg-danger bg-opacity-10 text-danger">⚠️ Báo cáo sự cố</span>
                        @else
                            <span class="badge bg-info bg-opacity-10 text-info">🎯 Đánh giá hệ thống</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <p class="small text-muted mb-2">Trạng Thái Hiện Tại</p>
                        @php
                            $statusBadge = match ($feedback->status) {
                                'pending' => 'bg-warning',
                                'viewed' => 'bg-info',
                                'resolved' => 'bg-success',
                            };
                            $statusLabel = match ($feedback->status) {
                                'pending' => '⏳ Chưa xem',
                                'viewed' => '👀 Đã xem',
                                'resolved' => '✅ Xử lý',
                            };
                        @endphp
                        <span class="badge {{ $statusBadge }} bg-opacity-10 text-dark">{{ $statusLabel }}</span>
                    </div>

                    <div class="mb-3">
                        <p class="small text-muted mb-2">Ngày Tạo</p>
                        <p class="small m-0">{{ $feedback->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="small text-muted mb-2">Lần Cập Nhật</p>
                        <p class="small m-0">{{ $feedback->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Guide Info Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-bold m-0">Thông Tin HDV</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">Tên HDV</p>
                    <p class="fw-bold mb-3">{{ $feedback->guide->user->fullname ?? $feedback->guide->user->username }}</p>

                    <p class="small text-muted mb-2">Email</p>
                    <p class="small mb-3">{{ $feedback->guide->user->email }}</p>

                    <p class="small text-muted mb-2">Số Điện Thoại</p>
                    <p class="small mb-3">{{ $feedback->guide->user->phone ?? 'N/A' }}</p>

                    <a href="{{ route('admin.guides.show', $feedback->guide->guide_id) }}"
                        class="btn btn-sm btn-outline-primary w-100">
                        👤 Xem Hồ Sơ HDV
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('admin_reply').addEventListener('input', function () {
            document.getElementById('char-count').textContent = this.value.length;
        });

        // Initialize character count
        document.getElementById('char-count').textContent = document.getElementById('admin_reply').value.length;
    </script>
@endsection