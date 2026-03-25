@extends('layouts.app')

@section('title', 'Bảng điều khiển Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Bảng điều khiển</h2>
            <p class="text-muted mb-0">Chào mừng trở lại, <span
                    class="fw-semibold text-primary">{{ auth()->user()->fullname ?? auth()->user()->username }}</span>!</p>
        </div>

    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #3b82f6 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small fw-medium mb-1 text-uppercase">Tổng số tour</div>
                            <div class="fs-2 fw-bold text-dark">{{ \DB::table('tours')->count() }}</div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                            <i class="fas fa-map-marked-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #10b981 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small fw-medium mb-1 text-uppercase">Đơn đặt tour</div>
                            <div class="fs-2 fw-bold text-dark">{{ \DB::table('booking')->count() }}</div>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #8b5cf6 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small fw-medium mb-1 text-uppercase">Khách hàng</div>
                            <div class="fs-2 fw-bold text-dark">{{ \DB::table('customer')->count() }}</div>
                        </div>
                        <div class="bg-purple bg-opacity-10 p-3 rounded-3"
                            style="color: #8b5cf6; background-color: rgba(139, 92, 246, 0.1);">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #f59e0b !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small fw-medium mb-1 text-uppercase">Hướng dẫn viên</div>
                            <div class="fs-2 fw-bold text-dark">{{ \DB::table('guide')->count() }}</div>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-3 text-warning">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection