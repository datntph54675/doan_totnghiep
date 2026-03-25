@extends('layouts.app')

@section('title', 'Quản lý Khách hàng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quản lý Khách hàng</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                            class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Khách hàng</li>
                </ol>
            </nav>
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
                            <th class="py-3 text-uppercase text-muted small fw-bold">Vai trò</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold">Ngày tạo</th>
                            <th class="pe-4 py-3 text-uppercase text-muted small fw-bold text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="{{ $user->status === 'inactive' ? 'opacity-50' : '' }}">
                                <td class="ps-4">
                                    <span class="fw-bold text-secondary">#{{ $user->user_id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $user->fullname }}</div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $user->email }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $user->role }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ optional($user->created_at)->format('d/m/Y') }}</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group">
                                        <a href="{{ url('admin/users/' . $user->user_id . '/edit') }}"
                                            class="btn btn-sm btn-outline-warning me-2" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.toggle-status', $user->user_id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $user->status === 'active' ? 'Ẩn' : 'Hiện' }}">
                                                <i class="fas fa-{{ $user->status === 'active' ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <br>Không có người dùng nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($users, 'links'))
            <div class="card-footer bg-white border-top-0 py-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
