@extends('layouts.app')

@section('title', 'Chi tiết Danh mục')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}"
                        class="text-decoration-none">Danh mục</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark">Chi tiết Danh mục</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning px-4 shadow-sm text-white">
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
                                    <th class="py-3 text-muted fw-semibold text-uppercase small" style="width: 200px;">ID Hệ
                                        thống</th>
                                    <td class="py-3 fw-bold text-dark">#{{ $category->category_id }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Tên danh mục</th>
                                    <td class="py-3 fs-5 fw-bold text-primary">{{ $category->name }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Trạng thái</th>
                                    <td class="py-3">
                                        @if($category->status == 'active' || $category->status == 1)
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
                                <tr>
                                    <th class="py-3 text-muted fw-semibold text-uppercase small">Mô tả</th>
                                    <td class="py-3 text-secondary leading-relaxed">
                                        {{ $category->description ?: 'Không có mô tả cho danh mục này.' }}
                                    </td>
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