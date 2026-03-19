@extends('admin.layout')

@section('content')
<div class="top-row">
    <div>
        <h2 style="margin:0">Bảng điều khiển</h2>
        <div class="muted-sm">Chào mừng, {{ auth()->user()->fullname ?? auth()->user()->username }}.</div>
    </div>
</div>

<div class="quick" style="margin-top:6px">
    <a href="{{ route('admin.tours.index') }}">Quản lý Tour</a>
    <a href="{{ route('admin.categories.index') }}">Danh mục</a>
    <a href="{{ route('admin.dashboard') }}">Làm mới</a>
</div>

<div class="grid" style="margin-top:12px">
    <div class="card">
        <div class="label">Tổng số tour</div>
        <div class="value">{{ \DB::table('tour')->count() }}</div>
    </div>
    <div class="card">
        <div class="label">Đơn đặt tour</div>
        <div class="value">{{ \DB::table('booking')->count() }}</div>
    </div>
    <div class="card">
        <div class="label">Khách hàng</div>
        <div class="value">{{ \DB::table('customer')->count() }}</div>
    </div>
    <div class="card">
        <div class="label">Hướng dẫn viên</div>
        <div class="value">{{ \DB::table('guide')->count() }}</div>
    </div>
</div>

@endsection