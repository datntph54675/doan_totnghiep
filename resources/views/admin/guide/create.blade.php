@extends('layouts.app')

@section('title', 'Thêm Hướng dẫn viên mới')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.guides.index') }}"
                        class="text-decoration-none">Hướng dẫn viên</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
        <h2 class="fw-bold text-dark">Thêm Hướng dẫn viên mới</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.guides.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="fullname" class="form-label fw-bold text-secondary">Tên người dùng</label>
                        <input type="text" name="fullname" id="fullname" value="{{ old('fullname') }}"
                            class="form-control @error('fullname') is-invalid @enderror"
                            placeholder="Nhập tên người dùng...">
                        @error('fullname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="email" class="form-label fw-bold text-secondary">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Nhập email...">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    
                    <div class="col-md-6 mb-4">
                        <label for="password" class="form-label fw-bold text-secondary">Mật Khẩu</label>
                        <input type="password" name="password" id="password" value="{{ old('password') }}"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Nhập mật khẩu...">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="password_confirmation" class="form-label fw-bold text-secondary">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}"
                            class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Nhập lại mật khẩu...">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- phone -->
                    <div>
                        <div class="col-md-6 mb-4">
                            <label for="phone" class="form-label fw-bold text-secondary">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Nhập số điện thoại...">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="cccd" class="form-label fw-bold text-secondary">CCCD</label>
                        <input type="text" name="cccd" id="cccd" value="{{ old('cccd') }}"
                            class="form-control @error('cccd') is-invalid @enderror" placeholder="Nhập số CCCD...">
                        @error('cccd')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="language" class="form-label fw-bold text-secondary">Ngôn ngữ</label>
                        <input type="text" name="language" id="language" value="{{ old('language') }}"
                            class="form-control @error('language') is-invalid @enderror" placeholder="Nhập ngôn ngữ...">
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="certificate" class="form-label fw-bold text-secondary">Chứng chỉ</label>
                        <input type="text" name="certificate" id="certificate" value="{{ old('certificate') }}"
                            class="form-control @error('certificate') is-invalid @enderror" placeholder="Nhập chứng chỉ...">
                        @error('certificate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="specialization" class="form-label fw-bold text-secondary">Chuyên môn</label>
                        <input type="text" name="specialization" id="specialization" value="{{ old('specialization') }}"
                            class="form-control @error('specialization') is-invalid @enderror" placeholder="Nhập chuyên môn...">
                        @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label for="experience" class="form-label fw-bold text-secondary">Kinh nghiệm</label>
                        <textarea name="experience" id="experience" rows="4"
                            class="form-control @error('experience') is-invalid @enderror"
                            placeholder="Nhập kinh nghiệm...">{{ old('experience') }}</textarea>
                        @error('experience')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4 text-secondary opacity-25">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        Lưu Hướng dẫn viên
                    </button>
                    <a href="{{ route('admin.guides.index') }}" class="btn btn-light border px-4 text-muted">
                        Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection