@extends('admin.layout')

@section('content')
<div class="container">
    <h1>Sửa Danh mục</h1>
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Tên</label>
            <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea name="description" class="form-control">{{ $category->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select name="status" class="form-control">
                <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Hiện</option>
                <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection