@extends('layouts.app')

@section('title', 'Quản lý Tour')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quản lý Tour Du Lịch</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                            class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Danh sách Tour</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.tours.create') }}" class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus me-2"></i> Thêm Tour mới
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted small fw-bold" style="width: 80px;">ID</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">Thông tin Tour</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">Danh mục</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold text-center">Giá</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold text-center">Trạng thái</th>
                            <th class="pe-4 py-3 text-uppercase text-muted small fw-bold text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tours as $tour)
                            <tr>
                                <td class="ps-4 text-muted fw-medium">#{{ $tour->tour_id }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $tour->name }}</div>

                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info px-3">
                                        <i class="fas fa-folder me-1"></i> {{ $tour->category ? $tour->category->name : 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-danger">{{ number_format($tour->price) }}
                                        <small>VND</small></span>
                                </td>
                                <td class="text-center">
                                    @if($tour->status == 'active' || $tour->status == 1)
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Đang bán</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Tạm
                                            dừng</span>
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
                                        <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc muốn ẩn tour này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa/Ẩn">
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

        @if(method_exists($tours, 'links'))
            <div class="card-footer bg-white border-top py-3">
                {{ $tours->links() }}
            </div>
        @endif
    </div>
@endsection