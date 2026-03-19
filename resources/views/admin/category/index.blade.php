@extends('admin.layout')

@section('content')
<style>
    .card {
        background: #fff;
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 8px 30px rgba(2, 6, 23, 0.06)
    }

    table.admin-table {
        width: 100%;
        border-collapse: collapse
    }

    table.admin-table thead th {
        background: #f3f7ff;
        padding: 10px;
        text-align: left
    }

    table.admin-table tbody td {
        padding: 10px;
        border-top: 1px solid #f1f5f9
    }

    .actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end
    }

    .btn-logout {
        background: #0f62fe;
        color: #fff;
        padding: 8px 12px;
        border-radius: 8px;
        border: 0
    }
</style>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
    <h2>Quản lý Danh mục</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn-logout">Thêm Danh mục</a>
</div>

@if(session('success'))
<div style="padding:10px;background:#e6ffed;border:1px solid #b7f1c9;margin-bottom:12px">{{ session('success') }}</div>
@endif

<div class="card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Mô tả</th>
                <th style="text-align:right">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <tr>
                <td>{{ $cat->category_id }}</td>
                <td>{{ $cat->name }}</td>
                <td style="max-width:400px;white-space:pre-wrap">{{ Str::limit($cat->description,200) }}</td>
                <td style="text-align:right">
                    <div class="actions">
                        <a class="btn-logout" href="{{ route('admin.categories.edit', $cat->category_id) }}">Sửa</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat->category_id) }}">@csrf @method('DELETE')
                            <button type="submit" style="background:transparent;border:0;color:#d9534f;cursor:pointer">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:12px">{{ $categories->links() }}</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('form button').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                var ok = confirm('Bạn có chắc muốn xóa mục này?');
                if (!ok) e.preventDefault();
            });
        });
    });
</script>

@endsection