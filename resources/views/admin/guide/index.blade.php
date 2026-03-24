@extends('layouts.app')

@section('content')
    <h2>Quản lý hướng dẫn viên</h2>

    {{-- @if(session('success'))
        <div style="background:#dff0d8;padding:10px;border-radius:6px;margin-bottom:12px">{{ session('success') }}</div>
    @endif --}}

    <div style="margin-bottom:12px">
        <a href="#" style="background:#0f62fe;color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none;cursor:pointer">Duyệt hướng dẫn viên</a>
    </div>

    <table style="width:100%;border-collapse:collapse">
        <thead>
            <tr style="text-align:left;border-bottom:1px solid #e5e7eb">
                <th style="padding:8px">ID</th>
                <th style="padding:8px">Tên</th>
                <th style="padding:8px">Email</th>
                <th style="padding:8px">SĐT</th>
                <th style="padding:8px">Trạng thái</th>
                <th style="padding:8px">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guides as $guide)
                <tr style="border-bottom:1px solid #f3f4f6; {{ $guide->status === 'inactive' ? 'opacity: 0.5;' : '' }}">
                    <td style="padding:8px">{{ $guide->guide_id }}</td>
                    <td style="padding:8px">{{ $guide->user->fullname ?? 'N/A' }}</td>
                    <td style="padding:8px">{{ $guide->user->email ?? 'N/A' }}</td>
                    <td style="padding:8px">{{ $guide->user->phone ?? 'N/A' }}</td>
                    <td style="padding:8px">{{ $guide->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}</td>
                    <td style="padding:8px">
                        <a href="{{ route('admin.guides.show', $guide->guide_id) }}" style="background:#0f62fe;color:#fff;padding:6px 10px;border-radius:6px;text-decoration:none;margin-right:5px">Xem</a>
                        <form action="{{ route('admin.guides.toggle-status', $guide->guide_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background:{{ $guide->status === 'active' ? '#dc3545' : '#28a745' }};color:#fff;padding:6px 10px;border-radius:6px;border:none;cursor:pointer">
                                {{ $guide->status === 'active' ? 'Ẩn' : 'Hiện' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding:12px">Không có hướng dẫn viên nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px">
        @if(method_exists($guides, 'links'))
            {{ $guides->links() }}
        @endif
    </div>
@endsection