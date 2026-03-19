@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Quản lý Danh mục</h1>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">Thêm Danh mục</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->category_id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                <td>{{ $category->status }}</td>
                <td>
                    <a href="{{ route('categories.show', $category) }}" class="btn btn-info">Xem</a>
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Sửa</a>
                    <!-- <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
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