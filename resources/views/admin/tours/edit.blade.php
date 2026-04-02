@extends('layouts.app')

@section('title', 'Chỉnh sửa Tour')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}"
                        class="text-decoration-none">Tour</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>
        <h2 class="fw-bold text-dark">Sửa Tour</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.tours.update', $tour) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="category_id" class="form-label fw-bold text-secondary">Danh mục</label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Chọn danh mục</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" {{ old('category_id', $tour->category_id) == $category->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="name" class="form-label fw-bold text-secondary">Tên Tour</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $tour->name) }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Nhập tên tour..." required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="price" class="form-label fw-bold text-secondary">Giá</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $tour->price) }}"
                            class="form-control @error('price') is-invalid @enderror" step="0.01" placeholder="Nhập giá..." required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="duration" class="form-label fw-bold text-secondary">Thời gian (ngày)</label>
                        <input type="number" name="duration" id="duration" value="{{ old('duration', $tour->duration) }}"
                            class="form-control @error('duration') is-invalid @enderror" placeholder="Nhập số ngày...">
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="supplier" class="form-label fw-bold text-secondary">Nhà cung cấp</label>
                        <input type="text" name="supplier" id="supplier" value="{{ old('supplier', $tour->supplier) }}"
                            class="form-control @error('supplier') is-invalid @enderror" placeholder="Nhập nhà cung cấp...">
                        @error('supplier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="status" class="form-label fw-bold text-secondary">Trạng thái</label>
                        <select name="status" id="status" class="form-select">
                            <option value="active" {{ old('status', $tour->status) == 'active' ? 'selected' : '' }}>Hiện (Active)</option>
                            <option value="inactive" {{ old('status', $tour->status) == 'inactive' ? 'selected' : '' }}>Ẩn (Inactive)</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="image" class="form-label fw-bold text-secondary">Hình ảnh</label>
                        <input type="file" name="image" id="image"
                            class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @if($tour->image_url)
                            <div class="mt-3">
                                <div class="small text-muted mb-2">Ảnh hiện tại</div>
                                <img src="{{ $tour->image_url }}" alt="{{ $tour->name }}"
                                    class="img-fluid rounded border" style="max-height: 180px; object-fit: cover;">
                            </div>
                        @endif
                        <small class="text-muted d-block mt-2">Tải ảnh mới để thay thế ảnh hiện tại. Định dạng: JPG, PNG, GIF, WEBP. Tối đa 2MB.</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <!-- Placeholder for alignment -->
                    </div>

                    <div class="col-12 mb-4">
                        <label for="description" class="form-label fw-bold text-secondary">Mô tả</label>
                        <textarea name="description" id="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Nhập mô tả chi tiết...">{{ old('description', $tour->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label for="policy" class="form-label fw-bold text-secondary">Chính sách</label>
                        <textarea name="policy" id="policy" rows="4"
                            class="form-control @error('policy') is-invalid @enderror"
                            placeholder="Nhập chính sách...">{{ old('policy', $tour->policy) }}</textarea>
                        @error('policy')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        Cập nhật
                    </button>
                    <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary px-4">
                        Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
