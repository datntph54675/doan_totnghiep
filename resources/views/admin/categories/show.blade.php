@extends('admin.layout')

@section('content')
<div class="container">
    <h1>Chi tiết Danh mục</h1>
    <p><strong>ID:</strong> {{ $category->category_id }}</p>
    <p><strong>Tên:</strong> {{ $category->name }}</p>
    <p><strong>Mô tả:</strong> {{ $category->description }}</p>
    <p><strong>Trạng thái:</strong> {{ $category->status }}</p>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
</div>
@endsection