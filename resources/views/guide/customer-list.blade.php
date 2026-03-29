@extends('guide.layout')

@section('page-title', 'Danh sách khách hàng')
@section('page-sub', 'Danh sách khách hàng đã đặt tour của bạn')

@section('content')

    @if($customers->isEmpty())
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
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên khách hàng</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Tour</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $index => $customer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $customer->fullname ?? 'N/A' }}</td>
                                <td>{{ $customer->email ?? 'N/A' }}</td>
                                <td>{{ $customer->phone ?? 'N/A' }}</td>
                                <td>{{ $customer->bookings->first()->schedule->tour->name ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
