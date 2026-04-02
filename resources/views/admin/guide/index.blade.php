@extends('layouts.app')

@section('title', 'Quản lý Hướng dẫn viên')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quản lý Hướng dẫn viên</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                            class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Hướng dẫn viên</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.guides.create') }}" class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus me-2"></i> Thêm hướng dẫn viên
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.guides.index') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên, email..." value="{{ request('keyword') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Trạng thái --</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> Lọc</button>
                    <a href="{{ route('admin.guides.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
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
                            <th class="py-3 text-uppercase text-muted small fw-bold">Tên</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">Email</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">SĐT</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">Trạng thái</th>
                            <th class="pe-4 py-3 text-uppercase text-muted small fw-bold text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guides as $guide)
                            <tr class="{{ $guide->status === 'inactive' ? 'opacity-50' : '' }}">
                                <td class="ps-4">
                                    <span class="fw-bold text-secondary">#{{ $guide->guide_id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $guide->user->fullname ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $guide->user->email ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $guide->user->phone ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($guide->status == 'active' || $guide->status == 1)
                                        <span class="badge bg-success-subtle text-success px-3 py-2">Hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Không hoạt động</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.guides.show', $guide->guide_id) }}"
                                            class="btn btn-sm btn-outline-info me-2" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.guides.toggle-status', $guide->guide_id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $guide->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $guide->status === 'active' ? 'Ẩn' : 'Hiện' }}">
                                                <i class="fas fa-{{ $guide->status === 'active' ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <br>Không có hướng dẫn viên nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($guides, 'links'))
            <div class="card-footer bg-white border-top-0 py-3">
                {{ $guides->links() }}
            </div>
        @endif
    </div>
@endsection
