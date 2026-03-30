@extends('layouts.app')

@section('title', 'Phản Hồi Từ Hướng Dẫn Viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark m-0">Phản Hồi Từ Hướng Dẫn Viên</h2>
                <p class="text-muted small mb-0">Hệ thống tiếp nhận báo cáo và đánh giá từ đội ngũ thực địa</p>
            </div>
        </div>

        <div class="row mb-4 g-3">
            @php
                $cardStats = [
                    ['label' => 'Tổng Phản Hồi', 'value' => $stats['total'], 'color' => 'primary', 'icon' => 'fa-comments'],
                    ['label' => 'Chưa Xem', 'value' => $stats['pending'], 'color' => 'warning', 'icon' => 'fa-clock'],
                    ['label' => 'Đã Xem', 'value' => $stats['viewed'], 'color' => 'info', 'icon' => 'fa-eye'],
                    ['label' => 'Đã Xử Lý', 'value' => $stats['resolved'], 'color' => 'success', 'icon' => 'fa-check-circle'],
                ];
            @endphp

            @foreach($cardStats as $card)
                <div class="col-md-3 col-sm-6">
                    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-{{ $card['color'] }} bg-opacity-10 p-3 rounded-3">
                                    <i class="fas {{ $card['icon'] }} text-{{ $card['color'] }} fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-muted small fw-medium mb-0">{{ $card['label'] }}</p>
                                    <h4 class="fw-bold m-0 text-dark">{{ number_format($card['value']) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-body p-3">
                <form method="GET" class="row g-2">
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i
                                    class="fas fa-filter"></i></span>
                            <select name="status" class="form-select border-start-0 ps-0">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" @selected(request('status') === 'pending')>Chưa xem</option>
                                <option value="viewed" @selected(request('status') === 'viewed')>Đã xem</option>
                                <option value="resolved" @selected(request('status') === 'resolved')>Đã xử lý</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i
                                    class="fas fa-tag"></i></span>
                            <select name="type" class="form-select border-start-0 ps-0">
                                <option value="">Tất cả loại</option>
                                <option value="danh_gia" @selected(request('type') === 'danh_gia')>Đánh giá hệ thống</option>
                                <option value="su_co" @selected(request('type') === 'su_co')>Báo cáo sự cố</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i
                                    class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Tìm theo tiêu đề, nội dung..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-3 flex-grow-1">Lọc</button>
                        <a href="{{ route('admin.guide-feedback.index') }}"
                            class="btn btn-light btn-sm border px-2 text-muted" title="Làm mới">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-nowrap">
                                <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary" style="width: 60px;">ID
                                </th>
                                <th class="py-3 text-uppercase small fw-bold text-secondary">Người Gửi</th>
                                <th class="py-3 text-uppercase small fw-bold text-secondary">Phân Loại</th>
                                <th class="py-3 text-uppercase small fw-bold text-secondary">Nội Dung Tiêu Đề</th>
                                <th class="py-3 text-uppercase small fw-bold text-secondary">Trạng Thái</th>
                                <th class="py-3 text-uppercase small fw-bold text-secondary">Thời Gian Gửi</th>
                                <th class="py-3 text-uppercase small fw-bold text-secondary text-end pe-4">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($feedbacks as $feedback)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-medium text-muted">#{{ $feedback->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 35px; height: 35px;">
                                                <i class="fas fa-user-tie small"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-bold text-dark small">
                                                    {{ $feedback->guide->user->fullname ?? $feedback->guide->user->username }}
                                                </div>
                                                <div class="text-muted small" style="font-size: 11px;">
                                                    {{ $feedback->guide->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($feedback->type === 'su_co')
                                            <span class="badge rounded-pill bg-danger-subtle text-danger px-2 py-1"
                                                style="font-size: 11px;">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Sự cố
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-info-subtle text-info px-2 py-1"
                                                style="font-size: 11px;">
                                                <i class="fas fa-star me-1"></i> Đánh giá
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="small text-dark mb-0 fw-medium">{{ Str::limit($feedback->title, 40) }}</p>
                                    </td>
                                    <td>
                                        @php
                                            $status = match ($feedback->status) {
                                                'pending' => ['bg' => 'warning', 'label' => 'Chờ xem'],
                                                'viewed' => ['bg' => 'info', 'label' => 'Đã xem'],
                                                'resolved' => ['bg' => 'success', 'label' => 'Đã xử lý'],
                                            };
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $status['bg'] }} p-1 rounded-circle me-2"
                                                style="width: 8px; height: 8px; display: inline-block;"></span>
                                            <span class="small text-{{ $status['bg'] }} fw-bold">{{ $status['label'] }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small text-muted">
                                            <div class="mb-0 text-dark">{{ $feedback->created_at->format('d/m/Y') }}</div>
                                            <div style="font-size: 11px;">{{ $feedback->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.guide-feedback.show', $feedback->id) }}"
                                            class="btn btn-sm btn-white border shadow-sm px-3 rounded-pill text-primary fw-bold"
                                            style="font-size: 12px;">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="Empty"
                                            style="width: 60px;" class="opacity-25 mb-3">
                                        <p class="text-muted fw-medium">Chưa nhận được phản hồi nào từ hướng dẫn viên</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($feedbacks->hasPages())
                    <div class="p-4 bg-light border-top d-flex justify-content-center">
                        {{ $feedbacks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Custom CSS để bổ sung nếu chưa có trong Bootstrap */
        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1) !important;
        }

        .btn-white:hover {
            background-color: #f8f9fa;
        }

        .table> :not(caption)>*>* {
            border-bottom-width: 1px;
            border-color: #f0f0f0;
        }
    </style>
@endsection