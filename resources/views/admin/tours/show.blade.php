@extends('layouts.app')

@section('title', 'Chi tiết Tour')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}"
                        class="text-decoration-none">Tour</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark">Chi tiết Tour</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
                <a href="{{ route('admin.tours.edit', $tour) }}" class="btn btn-warning px-4 shadow-sm text-white">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Thông tin chi tiết
                    </h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small" style="width: 200px;">ID Hệ thống</th>
                                    <td class="py-3 fw-bold text-dark">#{{ $tour->tour_id }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Tên Tour</th>
                                    <td class="py-3 fs-5 fw-bold text-primary">{{ $tour->name }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Danh mục</th>
                                    <td class="py-3">{{ $tour->category ? $tour->category->name : 'N/A' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Giá</th>
                                    <td class="py-3 fw-bold text-success">{{ number_format($tour->price) }} VND</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Thời gian</th>
                                    <td class="py-3">{{ $tour->duration }} ngày</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Nhà cung cấp</th>
                                    <td class="py-3">{{ $tour->supplier ?: 'Chưa cập nhật' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Hình ảnh</th>
                                    <td class="py-3">
                                        @if($tour->image_url)
                                            <div class="d-flex flex-column gap-2">
                                                <img src="{{ $tour->image_url }}" alt="{{ $tour->name }}"
                                                    class="rounded border" style="max-width:220px; max-height:140px; object-fit:cover;">
                                                <span class="text-muted small">{{ $tour->image }}</span>
                                            </div>
                                        @else
                                            Chưa có hình ảnh
                                        @endif
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Trạng thái</th>
                                    <td class="py-3">
                                        @if($tour->status == 'active' || $tour->status == 1)
                                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                <i class="fas fa-check-circle me-1"></i> Đang hoạt động
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
                                                <i class="fas fa-eye-slash me-1"></i> Đang ẩn
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Mô tả</th>
                                    <td class="py-3 text-secondary leading-relaxed">
                                        {{ $tour->description ?: 'Không có mô tả cho tour này.' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Chính sách</th>
                                    <td class="py-3 text-secondary leading-relaxed">
                                        {{ $tour->policy ?: 'Không có chính sách cho tour này.' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i> Dữ liệu được cập nhật từ hệ thống quản trị.
                        </small>
                        <a href="{{ route('admin.tours.departure-schedules.index', $tour->tour_id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-calendar me-1"></i> Xem lịch xuất phát
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
