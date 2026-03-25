@extends('layouts.app')

@section('title', 'Chỉnh sửa Khách hàng')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}"
                        class="text-decoration-none">Khách hàng</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>
        <h2 class="fw-bold text-dark">Sửa Khách hàng</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ url('admin/users/'.$user->user_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="fullname" class="form-label fw-bold text-secondary">Tên</label>
                        <input type="text" name="fullname" id="fullname" value="{{ old('fullname', $user->fullname) }}"
                            class="form-control @error('fullname') is-invalid @enderror" placeholder="Nhập tên..." required>
                        @error('fullname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="email" class="form-label fw-bold text-secondary">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Nhập email..." required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        Cập nhật
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">
                        Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
