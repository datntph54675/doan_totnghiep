@extends('layouts.app')

@section('title', 'Quản lý Tour')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quản lý Tour</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                            class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tour</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.tours.create') }}" class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus me-2"></i> Thêm Tour mới
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tours.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên tour..." value="{{ request('keyword') }}">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">-- Tất cả danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->category_id }}" {{ request('category_id') == $cat->category_id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">-- Trạng thái --</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tạm ẩn</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> Lọc</button>
                    <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted small fw-bold" style="width: 80px;">ID</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">Tên Tour</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">Danh mục</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">Giá</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">Trạng thái</th>
                            <th class="pe-4 py-3 text-uppercase text-muted small fw-bold text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tours as $tour)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-secondary">#{{ $tour->tour_id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $tour->name }}</div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $tour->category ? $tour->category->name : 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">{{ number_format($tour->price) }} VND</span>
                                </td>
                                <td>
                                    @if($tour->status == 'active' || $tour->status == 1)
                                        <span class="badge bg-success-subtle text-success px-3 py-2">Đang hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Tạm ẩn</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.tours.show', $tour) }}"
                                            class="btn btn-sm btn-outline-info me-2" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.tours.edit', $tour) }}"
                                            class="btn btn-sm btn-outline-warning me-2" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.tours.departure-schedules.index', $tour->tour_id) }}"
                                            class="btn btn-sm btn-outline-success me-2" title="Lịch xuất phát">
                                            <i class="fas fa-calendar"></i>
                                        </a>
                                        <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST"
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
        @if($tours instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="card-footer bg-white border-top-0 py-3">
                {{ $tours->links() }}
            </div>
        @endif
    </div>
@endsection
