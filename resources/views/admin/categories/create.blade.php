@extends('layouts.app')

@section('title', 'Thêm Danh mục mới')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}"
                        class="text-decoration-none">Danh mục</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
        <h2 class="fw-bold text-dark">Thêm Danh mục mới</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-8 mb-4">
                        <label for="name" class="form-label fw-bold text-secondary">Tên danh mục</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nhập tên danh mục (ví dụ: Tour Biển Đảo)..." required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="status" class="form-label fw-bold text-secondary">Trạng thái mặc định</label>
                        <select name="status" id="status" class="form-select">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hiện (Active)</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ẩn (Inactive)
                            </option>
                        </select>
                    </div>

                    <div class="col-12 mb-4">
                        <label for="description" class="form-label fw-bold text-secondary">Mô tả danh mục</label>
                        <textarea name="description" id="description" rows="5"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Nhập mô tả ngắn gọn về danh mục này...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4 text-secondary opacity-25">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        Lưu danh mục
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-light border px-4 text-muted">
                        Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection