@extends('layouts.app')

@section('title', 'Chỉnh sửa Danh mục')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}"
                        class="text-decoration-none">Danh mục</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>
        <h2 class="fw-bold text-dark">Sửa Danh mục</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8 mb-4">
                        <label for="name" class="form-label fw-bold text-secondary">Tên danh mục</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Nhập tên danh mục..."
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="status" class="form-label fw-bold text-secondary">Trạng thái</label>
                        <select name="status" id="status" class="form-select">
                            <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Hiện (Active)
                            </option>
                            <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Ẩn (Inactive)
                            </option>
                        </select>
                    </div>

                    <div class="col-12 mb-4">
                        <label for="description" class="form-label fw-bold text-secondary">Mô tả</label>
                        <textarea name="description" id="description" rows="5"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Nhập mô tả chi tiết...">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        Cập nhật
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary px-4">
                        Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection