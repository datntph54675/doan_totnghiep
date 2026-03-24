@extends('layouts.app')

@section('content')
    <h2>Chi tiết hướng dẫn viên</h2>

    <div style="background:#f9f9f9;padding:20px;border-radius:8px;margin-bottom:20px">
        <h3>Thông tin cá nhân</h3>
        <p><strong>ID:</strong> {{ $guide->guide_id }}</p>
        <p><strong>Tên:</strong> {{ $guide->user->fullname ?? 'N/A' }}</p>
        <p><strong>Email:</strong> {{ $guide->user->email ?? 'N/A' }}</p>
        <p><strong>SĐT:</strong> {{ $guide->user->phone ?? 'N/A' }}</p>
        <p><strong>CCCD:</strong> {{ $guide->cccd ?? 'N/A' }}</p>
        <p><strong>Trạng thái:</strong> {{ $guide->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}</p>
        <p><strong>Ngày tạo:</strong> {{ optional($guide->user->created_at)->format('Y-m-d') }}</p>
    </div>

    <div style="background:#f9f9f9;padding:20px;border-radius:8px;margin-bottom:20px">
        <h3>Thông tin chuyên môn</h3>
        <p><strong>Ngôn ngữ:</strong> {{ $guide->language ?? 'N/A' }}</p>
        <p><strong>Chứng chỉ:</strong> {{ $guide->certificate ?? 'N/A' }}</p>
        <p><strong>Kinh nghiệm:</strong> {{ $guide->experience ?? 'N/A' }}</p>
        <p><strong>Chuyên môn:</strong> {{ $guide->specialization ?? 'N/A' }}</p>
    </div>

    <div style="display:flex;gap:8px;margin-top:20px">
        <a href="{{ route('admin.guides.index') }}" style="background:#6c757d;color:#fff;padding:10px 15px;border-radius:6px;text-decoration:none">Quay lại</a>
        <a href="{{ route('admin.guides.edit', $guide->guide_id) }}" style="background:#0f62fe;color:#fff;padding:10px 15px;border-radius:6px;text-decoration:none">Sửa</a>
    </div>
@endsection