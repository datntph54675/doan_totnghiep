@extends('admin.layout')

@section('title', 'Bảng điều khiển Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Bảng điều khiển Admin</h2>
        <p class="text-muted mb-0">Chào mừng, {{ auth()->user()->fullname ?? auth()->user()->username }}.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Tổng số tour</div>
                <div class="fs-3 fw-bold">{{ \DB::table('tours')->count() }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Đơn đặt tour</div>
                <div class="fs-3 fw-bold">{{ \DB::table('booking')->count() }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Khách hàng</div>
                <div class="fs-3 fw-bold">{{ \DB::table('customer')->count() }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="text-muted small">Hướng dẫn viên</div>
                <div class="fs-3 fw-bold">{{ \DB::table('guide')->count() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
