@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chi tiết Tour</h1>
    <p><strong>ID:</strong> {{ $tour->tour_id }}</p>
    <p><strong>Tên:</strong> {{ $tour->name }}</p>
    <p><strong>Danh mục:</strong> {{ $tour->category ? $tour->category->name : 'N/A' }}</p>
    <p><strong>Mô tả:</strong> {{ $tour->description }}</p>
    <p><strong>Chính sách:</strong> {{ $tour->policy }}</p>
    <p><strong>Nhà cung cấp:</strong> {{ $tour->supplier }}</p>
    <p><strong>Hình ảnh:</strong> {{ $tour->image }}</p>
    <p><strong>Giá:</strong> {{ number_format($tour->price) }} VND</p>
    <p><strong>Thời gian:</strong> {{ $tour->duration }} ngày</p>
    <p><strong>Trạng thái:</strong> {{ $tour->status }}</p>
    <div class="mt-3">
        <a href="{{ route('admin.tours.departure-schedules.index', $tour->tour_id) }}" class="btn btn-primary">Xem lịch xuất phát</a>
        <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</div>
@endsection