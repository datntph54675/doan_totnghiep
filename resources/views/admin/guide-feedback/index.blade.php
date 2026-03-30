@extends('layouts.app')

@section('title', 'Phản Hồi Từ Hướng Dẫn Viên')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark m-0">Phản Hồi Từ Hướng Dẫn Viên</h2>
            <p class="text-muted small mb-0">Quản lý các báo cáo, đánh giá và kiến nghị từ các hướng dẫn viên</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Tổng Phản Hồi</p>
                            <h4 class="fw-bold m-0">{{ $stats['total'] }}</h4>
                        </div>
                        <div style="font-size: 32px;">📊</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Chưa Xem</p>
                            <h4 class="fw-bold m-0" style="color: #f59e0b;">{{ $stats['pending'] }}</h4>
                        </div>
                        <div style="font-size: 32px;">⏳</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Đã Xem</p>
                            <h4 class="fw-bold m-0" style="color: #3b82f6;">{{ $stats['viewed'] }}</h4>
                        </div>
                        <div style="font-size: 32px;">👀</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Đã Xử Lý</p>
                            <h4 class="fw-bold m-0" style="color: #10b981;">{{ $stats['resolved'] }}</h4>
                        </div>
                        <div style="font-size: 32px;">✅</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Trạng Thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" @selected(request('status') === 'pending')>⏳ Chưa xem</option>
                        <option value="viewed" @selected(request('status') === 'viewed')>👀 Đã xem</option>
                        <option value="resolved" @selected(request('status') === 'resolved')>✅ Đã xử lý</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Loại Phản Hồi</label>
                    <select name="type" class="form-select">
                        <option value="">Tất cả loại</option>
                        <option value="danh_gia" @selected(request('type') === 'danh_gia')>🎯 Đánh giá hệ thống</option>
                        <option value="su_co" @selected(request('type') === 'su_co')>⚠️ Báo cáo sự cố</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Tìm Kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo tiêu đề hoặc nội dung..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">🔍 Tìm</button>
                    <a href="{{ route('admin.guide-feedback.index') }}" class="btn btn-secondary">↺</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Feedback Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary" style="width: 40px;">#</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Hướng Dẫn Viên</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Loại</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary" style="width: 35%;">Tiêu Đề</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary" style="width: 15%;">Trạng Thái</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary" style="width: 15%;">Ngày Gửi</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-end pe-4">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($feedbacks as $feedback)
                            <tr>
                                <td class="ps-4">
                                    <span class="small text-muted">{{ $feedback->id }}</span>
                                </td>
                                <td>
                                    <div>
                                        <p class="fw-bold small m-0">
                                            {{ $feedback->guide->user->fullname ?? $feedback->guide->user->username }}</p>
                                        <small class="text-muted">{{ $feedback->guide->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if ($feedback->type === 'su_co')
                                        <span class="badge bg-danger bg-opacity-10 text-danger">⚠️ Báo cáo sự cố</span>
                                    @else
                                        <span class="badge bg-info bg-opacity-10 text-info">🎯 Đánh giá</span>
                                    @endif
                                </td>
                                <td>
                                    <p class="small m-0">{{ Str::limit($feedback->title, 30) }}</p>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match ($feedback->status) {
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
                                    <span class="badge {{ $statusClass }} bg-opacity-10 text-dark">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $feedback->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.guide-feedback.show', $feedback->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        👁️ Xem
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted m-0">📭 Không có phản hồi nào</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($feedbacks->hasPages())
                <div class="p-3 border-top">
                    {{ $feedbacks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection