@extends('layouts.app')

@section('content')
    <h2>Quản lý hướng dẫn viên</h2>

    @if(session('success'))
        <div style="background:#dff0d8;padding:10px;border-radius:6px;margin-bottom:12px">{{ session('success') }}</div>
    @endif

    <div style="margin-bottom:12px">
        <a href="{{ route('admin.guides.create') }}" style="background:#0f62fe;color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none">Thêm hướng dẫn viên</a>
    </div>

    <table style="width:100%;border-collapse:collapse">
        <thead>
            <tr style="text-align:left;border-bottom:1px solid #e5e7eb">
                <th style="padding:8px">ID</th>
                <th style="padding:8px">Tên</th>
                <th style="padding:8px">Email</th>
                <th style="padding:8px">CCCD</th>
                <th style="padding:8px">Chứng chỉ</th>
                <th style="padding:8px">Kinh nghiệm</th>
                <th style="padding:8px">Ngôn ngữ</th>
                <th style="padding:8px">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guides as $guide)
                <tr style="border-bottom:1px solid #f3f4f6">
                    <td style="padding:8px">{{ $guide->guide_id }}</td>
                    <td style="padding:8px">{{ $guide->user->fullname ?? 'N/A' }}</td>
                    <td style="padding:8px">{{ $guide->user->email ?? 'N/A' }}</td>
                    <td style="padding:8px">{{ $guide->cccd ?? 'N/A' }}</td>
                    <td style="padding:8px">{{ $guide->certificate ?? 'N/A' }}</td>
                    <td style="padding:8px">{{ $guide->experience ?? 'N/A' }}</td>
                    <td style="padding:8px">{{ $guide->language ?? 'N/A' }}</td>
                    <td style="padding:8px">
                        <a href="{{ route('admin.guides.edit', $guide->guide_id) }}" style="background:#0f62fe;color:#fff;padding:6px 10px;border-radius:6px;text-decoration:none;margin-right:4px">Sửa</a>
                        {{-- <form action="{{ route('admin.guides.destroy', $guide->guide_id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:#da1e28;color:#fff;padding:6px 10px;border-radius:6px;border:0" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                        </form> --}}
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