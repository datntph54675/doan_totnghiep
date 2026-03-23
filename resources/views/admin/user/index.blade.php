@extends('layouts.app')

@section('content')
    <h2>Quản lý khách hàng</h2>

    {{-- @if (session('success'))
        <div style="background:#dff0d8;padding:10px;border-radius:6px;margin-bottom:12px">{{ session('success') }}</div>
    @endif --}}

    <table style="width:100%;border-collapse:collapse">
        <thead>
            <tr style="text-align:left;border-bottom:1px solid #e5e7eb">
                <th style="padding:8px">ID người dùng</th>
                <th style="padding:8px">Tên</th>
                <th style="padding:8px">Email</th>
                <th style="padding:8px">Vai trò</th>
                <th style="padding:8px">Ngày tạo</th>
                <th style="padding:8px">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr style="border-bottom:1px solid #f3f4f6; {{ $user->status === 'inactive' ? 'opacity: 0.5;' : '' }}">
                    <td style="padding:8px">{{ $user->user_id }}</td>
                    <td style="padding:8px">{{ $user->fullname }}</td>
                    <td style="padding:8px">{{ $user->email }}</td>
                    <td style="padding:8px">{{ $user->role }}</td>
                    <td style="padding:8px">{{ optional($user->created_at)->format('Y-m-d') }}</td>
                    <td style="padding:8px">
                        <a href="{{ url('admin/users/' . $user->user_id . '/edit') }}"
                            style="background:#0f62fe;color:#fff;padding:6px 10px;border-radius:6px;text-decoration:none;margin-right:5px">Sửa</a>
                        <form action="{{ route('admin.users.toggle-status', $user->user_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background:{{ $user->status === 'active' ? '#dc3545' : '#28a745' }};color:#fff;padding:6px 10px;border-radius:6px;border:none;cursor:pointer">
                                {{ $user->status === 'active' ? 'Ẩn' : 'Hiện' }}
                            </button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding:12px">Không có người dùng nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px">
        @if (method_exists($users, 'links'))
            {{ $users->links() }}
        @endif
    </div>
@endsection
