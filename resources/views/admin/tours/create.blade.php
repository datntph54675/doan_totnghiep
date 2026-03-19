@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Thêm Tour</h1>
    <form action="{{ route('admin.tours.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="category_id">Danh mục</label>
            <select name="category_id" class="form-control">
                <option value="">Chọn danh mục</option>
                @foreach($categories as $category)
                <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="name">Tên Tour</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="policy">Chính sách</label>
            <textarea name="policy" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="supplier">Nhà cung cấp</label>
            <input type="text" name="supplier" class="form-control">
        </div>
        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="text" name="image" class="form-control">
        </div>
        <div class="form-group">
            <label for="price">Giá</label>
            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" step="0.01" required>
            @error('price')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="max_people">Số người tối đa</label>
            <input type="number" name="max_people" class="form-control @error('max_people') is-invalid @enderror" required>
            @error('max_people')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="duration">Thời gian (ngày)</label>
            <input type="number" name="duration" class="form-control">
        </div>
        <div class="form-group">
            <label for="start_date">Ngày bắt đầu</label>
            <input type="date" name="start_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="end_date">Ngày kết thúc</label>
            <input type="date" name="end_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select name="status" class="form-control">
                <option value="active">Hiện</option>
                <option value="inactive">Ẩn</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
    
</div>
@endsection