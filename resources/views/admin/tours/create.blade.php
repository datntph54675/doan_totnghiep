@extends('layouts.app')

@section('title', 'Thêm Tour mới')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}" class="text-decoration-none">Quản lý
                        Tour</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
        <h2 class="fw-bold text-dark">Thêm Tour du lịch mới</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.tours.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-8 mb-4">
                        <label for="name" class="form-label fw-bold text-secondary">Tên Tour</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Nhập tên tour đầy đủ..."
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="category_id" class="form-label fw-bold text-secondary">Danh mục</label>
                        <select name="category_id" id="category_id"
                            class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Chọn danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->category_id }}"
                                    {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="price" class="form-label fw-bold text-secondary">Giá tour (VND)</label>
                        <div class="input-group">
                            <input type="number" name="price" id="price" value="{{ old('price') }}"
                                class="form-control @error('price') is-invalid @enderror" required>
                            <span class="input-group-text">₫</span>
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="max_people" class="form-label fw-bold text-secondary">Số người tối đa</label>
                        <input type="number" name="max_people" id="max_people" value="{{ old('max_people') }}"
                            class="form-control @error('max_people') is-invalid @enderror" required>
                        @error('max_people')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="duration" class="form-label fw-bold text-secondary">Thời gian (ngày)</label>
                        <input type="number" name="duration" id="duration" value="{{ old('duration') }}"
                            class="form-control">
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="start_date" class="form-label fw-bold text-secondary">Ngày bắt đầu</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                            class="form-control">
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="end_date" class="form-label fw-bold text-secondary">Ngày kết thúc</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="status" class="form-label fw-bold text-secondary">Trạng thái</label>
                        <select name="status" id="status" class="form-select">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hiện (Kinh doanh)
                            </option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ẩn (Tạm ngưng)
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="supplier" class="form-label fw-bold text-secondary">Nhà cung cấp</label>
                        <input type="text" name="supplier" id="supplier" value="{{ old('supplier') }}" class="form-control"
                            placeholder="Tên công ty đối tác...">
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="image" class="form-label fw-bold text-secondary">Đường dẫn hình ảnh</label>
                        <input type="text" name="image" id="image" value="{{ old('image') }}" class="form-control"
                            placeholder="URL hình ảnh hoặc tên file...">
                    </div>

                    <div class="col-12 mb-4">
                        <label for="description" class="form-label fw-bold text-secondary">Mô tả Tour</label>
                        <textarea name="description" id="description" rows="4" class="form-control"
                            placeholder="Thông tin chi tiết về tour...">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-12 mb-4">
                        <label for="policy" class="form-label fw-bold text-secondary">Chính sách & Quy định</label>
                        <textarea name="policy" id="policy" rows="3" class="form-control"
                            placeholder="Chính sách hoàn tiền, trẻ em, hủy tour...">{{ old('policy') }}</textarea>
                    </div>
                </div>

                <hr class="my-4 text-secondary opacity-25">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        Lưu Tour
                    </button>
                    <a href="{{ route('admin.tours.index') }}" class="btn btn-light border px-4 text-muted">
                        Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection