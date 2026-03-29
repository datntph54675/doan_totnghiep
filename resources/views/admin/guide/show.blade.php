@extends('layouts.app')

@section('title', 'Chi tiết Hướng dẫn viên')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.guides.index') }}"
                        class="text-decoration-none">Hướng dẫn viên</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark">Chi tiết Hướng dẫn viên</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.guides.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
                <a href="{{ route('admin.guides.edit', $guide->guide_id) }}" class="btn btn-warning px-4 shadow-sm text-white">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-user me-2"></i>Thông tin cá nhân
                    </h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small" style="width: 200px;">ID Hệ thống</th>
                                    <td class="py-3 fw-bold text-dark">#{{ $guide->guide_id }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Tên</th>
                                    <td class="py-3 fs-5 fw-bold text-primary">{{ $guide->user->fullname ?? 'N/A' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Email</th>
                                    <td class="py-3">{{ $guide->user->email ?? 'N/A' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">SĐT</th>
                                    <td class="py-3">{{ $guide->user->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">CCCD</th>
                                    <td class="py-3">{{ $guide->cccd ?? 'N/A' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Trạng thái</th>
                                    <td class="py-3">
                                        @if($guide->status == 'active' || $guide->status == 1)
                                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                <i class="fas fa-check-circle me-1"></i> Hoạt động
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
                                                <i class="fas fa-eye-slash me-1"></i> Không hoạt động
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Ngày tạo</th>
                                    <td class="py-3">{{ optional($guide->user->created_at)->format('d/m/Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-graduation-cap me-2"></i>Thông tin chuyên môn
                    </h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small" style="width: 200px;">Ngôn ngữ</th>
                                    <td class="py-3">{{ $guide->language ?: 'Chưa cập nhật' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Chứng chỉ</th>
                                    <td class="py-3">{{ $guide->certificate ?: 'Chưa cập nhật' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Kinh nghiệm</th>
                                    <td class="py-3 text-secondary leading-relaxed">
                                        {{ $guide->experience ?: 'Chưa có thông tin kinh nghiệm.' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Chuyên môn</th>
                                    <td class="py-3">{{ $guide->specialization ?: 'Chưa cập nhật' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-3 px-4">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i> Dữ liệu được cập nhật từ hệ thống quản trị.
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection
