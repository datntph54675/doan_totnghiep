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

    .muted-sm {
        color: #6b7280;
        font-size: 13px
    }

    .actions {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: flex-end;
        white-space: nowrap
    }

    .btn-primary {
        background: #0f62fe;
        color: #fff;
        padding: 6px 10px;
        border-radius: 8px;
        border: 0
    }

    .btn-outline {
        background: transparent;
        border: 1px solid #e6eef6;
        padding: 8px 12px;
        border-radius: 8px
    }
</style>

<div class="top-actions" style="margin-bottom:12px; display:flex; justify-content:space-between; align-items:center">
    <h2 style="margin:0">Đơn đặt tour</h2>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn-outline">← Quay lại</a>
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
                    <th style="padding:8px">#</th>
                    <th style="padding:8px">Booking ID</th>
                    <th style="padding:8px">Tour</th>
                    <th style="padding:8px">Ngày khởi hành</th>
                    <th style="padding:8px">Khách</th>
                    <th style="padding:8px">Số người</th>
                    <th style="padding:8px">Tổng tiền</th>
                    <th style="padding:8px">Đặt lúc</th>
                    <th style="padding:8px">Trạng thái</th>
                    <th style="text-align:center;padding:8px">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td style="padding:8px">{{ $loop->iteration + ($bookings->currentPage()-1)*$bookings->perPage() }}</td>
                    <td style="padding:8px">{{ $b->booking_id }}</td>
                    <td style="padding:8px">{{ $b->tour->name ?? '—' }}</td>
                    <td style="padding:8px">{{ $b->schedule && $b->schedule->start_date ? $b->schedule->start_date->format('d/m/Y') : '—' }}</td>
                    <td style="padding:8px">{{ $b->customer->fullname ?? '—' }}<br><span class="muted-sm">{{ $b->customer->phone ?? '' }}</span></td>
                    <td style="padding:8px">{{ $b->num_people }}</td>
                    <td style="padding:8px">{{ number_format($b->total_price,0,',','.') }} ₫</td>
                    <td style="padding:8px">{{ $b->booking_date ? $b->booking_date->format('d/m/Y H:i') : '—' }}</td>
                    <td style="padding:8px">
                        @if($b->admin_confirmed)
                        <span style="display:inline-block;padding:6px 8px;border-radius:999px;background:#e6f2ff;color:#0f62fe;font-weight:700">Đã xác nhận</span>
                        @else
                        <span style="display:inline-block;padding:6px 8px;border-radius:999px;background:#fff1f2;color:#d6333f;font-weight:700">Chưa xác nhận</span>
                        @endif
                    </td>
                    <td style="padding:8px;text-align:right">
                        <div class="actions">
                            @if(!$b->admin_confirmed)
                            <form method="POST" action="{{ route('admin.bookings.confirm', $b->booking_id) }}">@csrf
                                <button class="btn-primary" type="submit">Xác nhận</button>
                            </form>
                            @else
                            <span class="muted-sm">—</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="padding:12px">Không có đơn nào chờ xác nhận.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:12px">{{ $bookings->links() }}</div>
</div>

@endsection