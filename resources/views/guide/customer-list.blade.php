@extends('guide.layout')

@section('page-title', 'Danh sách khách hàng')
@section('page-sub', 'Danh sách khách hàng đã đặt tour của bạn')

@section('content')

@if($bookings->isEmpty())
<div class="card">
    <div class="card-body">
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <div class="empty-text">Hiện tại không có khách hàng nào đặt tour của bạn.</div>
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-header">
        <div class="card-title">Danh sách khách hàng</div>
        <div style="font-size:13px;color:var(--text-muted)">Tổng cộng {{ $totalPassengers }} khách</div>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên khách hàng</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Số người</th>
                    <th>Tour</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $booking->customer->fullname ?? 'N/A' }}
                        @if($booking->participant_count > 1)
                        <div style="font-size:12px;color:var(--text-muted)">Người đại diện nhóm đặt</div>
                        @endif
                    </td>
                    <td>{{ $booking->customer->email ?? 'N/A' }}</td>
                    <td>{{ $booking->customer->phone ?? 'N/A' }}</td>
                    <td>{{ $booking->participant_count }}</td>
                    <td>{{ $booking->schedule->tour->name ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection