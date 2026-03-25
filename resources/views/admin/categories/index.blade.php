@extends('admin.layout')

@section('title', 'Quản lý Danh mục')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quản lý Danh mục</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                            class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Danh mục</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus me-2"></i> Thêm Danh mục mới
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted small fw-bold" style="width: 80px;">ID</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">Tên danh mục</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">Mô tả</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">Trạng thái</th>
                            <th class="pe-4 py-3 text-uppercase text-muted small fw-bold text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-secondary">#{{ $category->category_id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $category->name }}</div>
                                </td>
                                <td>
                                    <span class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                        {{ $category->description ?? 'Chưa có mô tả' }}
                                    </span>
                                </td>
                                <td>
                                    @if($category->status == 'active' || $category->status == 1)
                                        <span class="badge bg-success-subtle text-success px-3 py-2">Đang hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Tạm ẩn</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.categories.show', $category) }}"
                                            class="btn btn-sm btn-outline-info me-2" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="btn btn-sm btn-outline-warning me-2" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="card-footer bg-white border-top-0 py-3">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection