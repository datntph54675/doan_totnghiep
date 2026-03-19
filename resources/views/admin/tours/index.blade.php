@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Quản lý Tour</h1>
    <a href="{{ route('tours.create') }}" class="btn btn-primary">Thêm Tour</a>
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
                    <a href="{{ route('tours.show', $tour) }}" class="btn btn-info">Xem</a>
                    <a href="{{ route('tours.edit', $tour) }}" class="btn btn-warning">Sửa</a>
                    <!-- <form action="{{ route('tours.destroy', $tour) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ẩn</button>
                    </form> -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
