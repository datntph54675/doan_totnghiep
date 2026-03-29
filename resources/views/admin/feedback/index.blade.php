@extends('layouts.app')

@section('title', 'Quản lý Feedback')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark m-0">Danh sách Feedback</h2>
            <p class="text-muted small mb-0">Xem và quản lý các đánh giá từ khách hàng</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary" style="width: 80px;">ID</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Thông tin Tour</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Đánh giá</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary" style="width: 35%;">Nội dung</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Ngày gửi</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($feedbacks as $feedback)
                            <tr>
                                <td class="ps-4">
                                    <span class="text-dark fw-medium">#{{ $feedback->id }}</span>
                                </td>
                                <td>
                                    <div class="small fw-bold text-dark">Booking #{{ $feedback->booking_id }}</div>
                                    <div class="text-muted" style="font-size: 11px;">Loại:
                                        <span class="text-primary text-capitalize">{{ $feedback->type }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($feedback->rating)
                                        <div class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star {{ $i <= $feedback->rating ? 'text-warning' : 'text-light' }} small"></i>
                                            @endfor
                                            <span
                                                class="ms-2 badge {{ $feedback->rating >= 4 ? 'bg-success-subtle text-success' : ($feedback->rating >= 3 ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger') }}">
                                                {{ $feedback->rating }}/5
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <p class="mb-0 small text-dark"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                                        {{ $feedback->content }}
                                    </p>
                                </td>
                                <td>
                                    <div class="small text-muted">
                                        <i
                                            class="far fa-calendar-alt me-1"></i>{{ $feedback->created_at ? $feedback->created_at->format('d/m/Y') : '—' }}
                                        <div style="font-size: 10px;">
                                            {{ $feedback->created_at ? $feedback->created_at->format('H:i') : '' }}</div>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    @if ($feedback->type !== 'hidden')
                                        <form action="{{ route('admin.feedback.hide', $feedback->id) }}" method="POST"
                                            class="d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning border-0 rounded-pill px-3"
                                                onclick="return confirm('Ẩn phản hồi này khỏi trang công khai?')">
                                                <i class="fas fa-eye-slash me-1"></i> Ẩn
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
                                            <i class="fas fa-lock me-1"></i> Đã ẩn
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-comment-slash fa-3x mb-3 d-block opacity-25"></i>
                                    Không có feedback nào từ khách hàng.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
