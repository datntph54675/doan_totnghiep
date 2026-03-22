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
    <p><strong>Số người tối đa:</strong> {{ $tour->max_people }}</p>
    <p><strong>Thời gian:</strong> {{ $tour->duration }} ngày</p>
    <p><strong>Ngày bắt đầu:</strong> {{ $tour->start_date }}</p>
    <p><strong>Ngày kết thúc:</strong> {{ $tour->end_date }}</p>
    <p><strong>Trạng thái:</strong> {{ $tour->status }}</p>
    <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">Quay lại</a>
</div>
@endsection