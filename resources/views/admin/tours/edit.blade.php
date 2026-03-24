@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sửa Tour</h1>
    <form action="{{ route('admin.tours.update', $tour) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="category_id">Danh mục</label>
            <select name="category_id" class="form-control">
                <option value="">Chọn danh mục</option>
                @foreach($categories as $category)
                <option value="{{ $category->category_id }}" {{ $tour->category_id == $category->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name">Tên Tour</label>
            <input type="text" name="name" value="{{ $tour->name }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea name="description" class="form-control">{{ $tour->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="policy">Chính sách</label>
            <textarea name="policy" class="form-control">{{ $tour->policy }}</textarea>
        </div>
        <div class="form-group">
            <label for="supplier">Nhà cung cấp</label>
            <input type="text" name="supplier" value="{{ $tour->supplier }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="text" name="image" value="{{ $tour->image }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="price">Giá</label>
            <input type="number" name="price" value="{{ $tour->price }}" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="duration">Thời gian (ngày)</label>
            <input type="number" name="duration" value="{{ $tour->duration }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select name="status" class="form-control">
                <option value="active" {{ $tour->status == 'active' ? 'selected' : '' }}>Hiện</option>
                <option value="inactive" {{ $tour->status == 'inactive' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection