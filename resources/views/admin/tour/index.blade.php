@extends('admin.layout')

@section('content')
<style>
    .table-card {
        background: #fff;
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 8px 30px rgba(2, 6, 23, 0.06)
    }

    .table-responsive {
        overflow: auto
    }

    table.admin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px
    }

    table.admin-table thead th {
        background: linear-gradient(90deg, #f3f7ff, #eef6ff);
        color: #0b2540;
        font-weight: 700;
        padding: 10px;
        text-align: left;
        white-space: nowrap
    }

    table.admin-table tbody td {
        padding: 10px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9
    }

    .thumb {
        width: 64px;
        height: 48px;
        object-fit: cover;
        border-radius: 6px;
        display: inline-block
    }

    .muted-sm {
        color: #6b7280;
        font-size: 13px
    }

    .badge-status {
        display: inline-block;
        padding: 6px 8px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 12px
    }

    .badge-active {
        background: #e6f2ff;
        color: #0f62fe
    }

    .badge-inactive {
        background: #fff1f2;
        color: #d6333f
    }

    /* action buttons container */
    .actions{
        display:flex;
        gap:8px;
        align-items:center;
        justify-content:flex-end;
        white-space:nowrap;
    }

    .actions form{display:inline-block;margin:0}

    .actions .btn-edit {
        background: #0f62fe;
        color: #fff;
        padding: 6px 10px;
        border-radius: 8px;
        text-decoration: none;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:68px;
    }

    .actions .btn-delete {
        background: transparent;
        border: 0;
        color: #d9534f;
        cursor: pointer;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 700;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:56px;
    }

    .actions .btn-edit,
    .actions .btn-delete{
        transition:transform .12s ease,box-shadow .12s ease,background .12s ease
    }

    .actions .btn-edit:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(15,98,254,0.12)}
    .actions .btn-delete:hover{background:rgba(217,83,79,0.06)}
    .actions .btn-delete:focus{outline:2px solid rgba(217,83,79,0.12)}

    .top-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        justify-content: space-between
    }

    @media(max-width:900px) {

        table.admin-table thead th,
        table.admin-table tbody td {
            font-size: 13px
        }
    }
</style>
<div class="top-actions" style="margin-bottom:12px">
    <h2 style="margin:0">Quản lý Tour</h2>
    <div>
        <a href="{{ route('admin.tours.create') }}" class="btn-logout">Thêm tour</a>
    </div>
</div>

@if(session('success'))
<div style="padding:10px;background:#e6ffed;border:1px solid #b7f1c9;margin-bottom:12px">{{ session('success') }}</div>
@endif

<div class="table-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="text-align:left;padding:8px">ID</th>
                    <th style="text-align:left;padding:8px">Category ID</th>
                    <th style="text-align:left;padding:8px">Tên</th>
                    <th style="text-align:left;padding:8px">Mô tả</th>
                    <th style="text-align:left;padding:8px">Chính sách</th>
                    <th style="text-align:left;padding:8px">Nhà cung cấp</th>
                    <th style="text-align:left;padding:8px">Ảnh</th>
                    <th style="text-align:left;padding:8px">Giá</th>
                    <th style="text-align:left;padding:8px">Số người</th>
                    <th style="text-align:left;padding:8px">Thời lượng ngày</th>
                    <th style="text-align:left;padding:8px">Ngày bắt đầu</th>
                    <th style="text-align:left;padding:8px">Ngày kết thúc</th>
                    <th style="text-align:left;padding:8px">Trạng thái</th>
                    <!-- <th style="text-align:left;padding:8px">Tạo lúc</th>
                    <th style="text-align:left;padding:8px">Cập nhật</th> -->
                    <th style="text-align:center;padding:8px">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tours as $tour)
                <tr style="border-top:1px solid #eee">
                    <td style="padding:8px">{{ $tour->tour_id }}</td>
                    <td style="padding:8px">{{ optional($tour->category)->name ?? $tour->category_id }}</td>
                    <td style="padding:8px">{{ $tour->name }}</td>
                    <td style="padding:8px;max-width:300px;white-space:pre-wrap">{{ Str::limit($tour->description, 200) }}</td>
                    <td style="padding:8px;max-width:200px;white-space:pre-wrap">{{ Str::limit($tour->policy, 120) }}</td>
                    <td style="padding:8px">{{ $tour->supplier }}</td>
                    <td style="padding:8px">
                        @if($tour->image)
                        @php $src = strpos($tour->image, 'http') === 0 ? $tour->image : asset('storage/'.$tour->image); @endphp
                        <img src="{{ $src }}" alt="" class="thumb">
                        @endif
                    </td>
                    <td style="padding:8px">{{ number_format($tour->price,0,',','.') }}</td>
                    <td style="padding:8px">{{ $tour->max_people }}</td>
                    <td style="padding:8px">{{ $tour->duration }}</td>
                    <td style="padding:8px">{{ $tour->start_date }}</td>
                    <td style="padding:8px">{{ $tour->end_date }}</td>
                    <td style="padding:8px">
                        <span class="badge-status {{ $tour->status == 'active' ? 'badge-active' : 'badge-inactive' }}">{{ $tour->status }}</span>
                    </td>
                    <!-- <td style="padding:8px">{{ $tour->created_at }}</td>
                    <td style="padding:8px">{{ $tour->updated_at }}</td> -->
                    <td style="padding:8px;text-align:right">
                        <div class="actions">
                            <a class="btn-edit" href="{{ route('admin.tours.edit', $tour->tour_id) }}">Sửa</a>
                            <form method="POST" action="{{ route('admin.tours.destroy', $tour->tour_id) }}" style="display:inline">@csrf @method('DELETE')
                                <button class="btn-delete" type="submit">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:12px">{{ $tours->links() }}</div>
</div>
<script>
    // Confirm before delete
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-delete').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                var ok = confirm('Bạn có chắc muốn xóa tour này?');
                if (!ok) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection