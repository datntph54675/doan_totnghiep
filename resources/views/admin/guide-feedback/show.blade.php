@extends('layouts.app')

@section('title', 'Chi Tiết Phản Hồi Từ Hướng Dẫn Viên')

@section('content')
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <div>
            <a href="{{ route('admin.guide-feedback.index') }}" class="text-decoration-none small fw-bold text-muted">
                <i class="fas fa-chevron-left me-1"></i> QUAY LẠI DANH SÁCH
            </a>
            <h2 class="fw-bold text-dark mt-2 m-0">{{ $feedback->title }}</h2>
        </div>
        <div class="text-end">
            @if ($feedback->type === 'su_co')
                <span class="badge bg-danger px-3 py-2 shadow-sm"><i class="fas fa-exclamation-triangle me-1"></i> Báo cáo sự
                    cố</span>
            @else
                <span class="badge bg-info px-3 py-2 shadow-sm"><i class="fas fa-lightbulb me-1"></i> Đánh giá hệ thống</span>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 45px; height: 45px;">
                            <i class="fas fa-user-tie fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">
                                {{ $feedback->guide->user->fullname ?? $feedback->guide->user->username }}</h6>
                            <small class="text-muted">{{ $feedback->created_at->format('d/m/Y H:i') }}
                                ({{ $feedback->created_at->diffForHumans() }})</small>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="p-3 rounded-3" style="background: #f8faff; border-left: 4px solid #0d6efd;">
                        <label class="small fw-bold text-primary text-uppercase mb-2 d-block"
                            style="letter-spacing: 1px;">Nội dung phản hồi:</label>
                        <div class="text-dark lh-base">
                            {!! nl2br(e($feedback->content)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold m-0"><i class="fas fa-reply me-2 text-warning"></i>Xử lý & Phản hồi của Admin</h6>
                </div>
                <div class="card-body">
                    @if ($feedback->admin_reply)
                        <div class="mb-4">
                            <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Phản hồi hiện tại:</label>
                            <div class="p-3 rounded-3 border-0 shadow-none"
                                style="background: #fff9e6; border: 1px dashed #ffeeba !important;">
                                <p class="m-0 text-dark" style="white-space: pre-wrap;">{!! nl2br(e($feedback->admin_reply)) !!}
                                </p>
                                <hr class="my-2 opacity-10">
                                <small class="text-muted italic"><i class="fas fa-history me-1"></i>Cập nhật lúc:
                                    {{ $feedback->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.guide-feedback.updateStatus', $feedback->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="admin_reply" class="form-label small fw-bold">Cập nhật nội dung phản hồi</label>
                            <textarea name="admin_reply" id="admin_reply" class="form-control bg-light border-0" rows="5"
                                placeholder="Gửi hướng dẫn viên ý kiến xử lý hoặc ghi chú nội bộ..."
                                style="border-radius: 10px;">{{ $feedback->admin_reply }}</textarea>
                            <div class="d-flex justify-content-end mt-1">
                                <small class="text-muted"><span id="char-count" class="fw-bold">0</span> / 5000 ký
                                    tự</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="status" class="form-label small fw-bold">Cập nhật trạng thái xử lý</label>
                                <select name="status" id="status" class="form-select border-0 bg-light"
                                    style="border-radius: 8px;">
                                    <option value="pending" @selected($feedback->status === 'pending')>⏳ Chưa xem</option>
                                    <option value="viewed" @selected($feedback->status === 'viewed')>👀 Đã xem</option>
                                    <option value="resolved" @selected($feedback->status === 'resolved')>✅ Đã xử lý xong
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2 border-top pt-3">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
                            </button>
                            <a href="{{ route('admin.guide-feedback.index') }}" class="btn btn-light px-4 border"
                                style="border-radius: 8px;">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Tóm tắt trạng thái</h6>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Mã phản hồi:</span>
                        <span class="fw-bold">#{{ $feedback->id }}</span>
                    </div>

                    @php
                        $statusData = match ($feedback->status) {
                            'pending' => ['class' => 'warning', 'label' => 'Chưa xem', 'icon' => 'fa-clock'],
                            'viewed' => ['class' => 'info', 'label' => 'Đã xem', 'icon' => 'fa-eye'],
                            'resolved' => ['class' => 'success', 'label' => 'Đã xử lý', 'icon' => 'fa-check-circle'],
                        };
                    @endphp

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Trạng thái:</span>
                        <span class="text-{{ $statusData['class'] }} fw-bold small">
                            <i class="fas {{ $statusData['icon'] }} me-1"></i>{{ $statusData['label'] }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Ngày gửi:</span>
                        <span class="small fw-medium text-dark">{{ $feedback->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Cập nhật:</span>
                        <span class="small fw-medium text-dark">{{ $feedback->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm border-top border-4 border-primary" style="border-radius: 15px;">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-primary">Thông tin Hướng Dẫn Viên</h6>

                    <div class="text-center mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 70px; height: 70px;">
                            <i class="fas fa-id-badge fs-2 text-secondary"></i>
                        </div>
                        <h6 class="fw-bold mb-0">{{ $feedback->guide->user->fullname ?? $feedback->guide->user->username }}
                        </h6>
                        <small class="text-muted">HDV Chuyên nghiệp</small>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2 small">
                            <i class="fas fa-envelope me-2 text-muted" style="width: 20px;"></i>
                            <span class="text-dark">{{ $feedback->guide->user->email }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2 small">
                            <i class="fas fa-phone-alt me-2 text-muted" style="width: 20px;"></i>
                            <span class="text-dark">{{ $feedback->guide->user->phone ?? 'Chưa cập nhật' }}</span>
                        </div>
                    </div>

                    <a href="{{ route('admin.guides.show', $feedback->guide->guide_id) }}"
                        class="btn btn-outline-primary w-100 btn-sm fw-bold" style="border-radius: 8px;">
                        <i class="fas fa-external-link-alt me-1"></i> Xem hồ sơ chi tiết
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const textarea = document.getElementById('admin_reply');
        const charCount = document.getElementById('char-count');

        textarea.addEventListener('input', function () {
            charCount.textContent = this.value.length;
        });

        // Init
        charCount.textContent = textarea.value.length;
    </script>
@endsection