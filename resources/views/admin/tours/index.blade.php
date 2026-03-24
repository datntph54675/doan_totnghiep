@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Quản lý Tour</h1>
    <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">Thêm Tour</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tours as $tour)
            <tr>
                <td>{{ $tour->tour_id }}</td>
                <td>{{ $tour->name }}</td>
                <td>{{ $tour->category ? $tour->category->name : 'N/A' }}</td>
                <td>{{ number_format($tour->price) }} VND</td>
                <td>{{ $tour->status }}</td>
                <td>
                    <a href="{{ route('admin.tours.show', $tour) }}" class="btn btn-info btn-sm">Xem</a>
                    <a href="{{ route('admin.tours.edit', $tour) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="{{ route('admin.tours.departure-schedules.index', $tour->tour_id) }}" class="btn btn-success btn-sm">Lịch xuất phát</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
