@extends('layouts.user')

@section('title', 'Lịch sử thanh toán - GoTour')

@section('styles')
<style>
    :root {
        --primary: #0066cc;
        --primary-dark: #004fa3;
        --text-dark: #1e293b;
        --text-gray: #64748b;
        --bg: #f8fafc;
        --white: #fff;
        --border: #e2e8f0;
    }

    .history-page {
        background: var(--bg);
        min-height: calc(100vh - 80px);
        padding: 40px 0 80px;
    }

    .container { max-width: 1000px; margin: 0 auto; padding: 0 20px; }

    .page-head {
        margin-top: 50px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 28px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        background: var(--white);
        color: var(--primary);
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        border: 1px solid var(--border);
        transition: background .2s;
    }
    .back-btn:hover { background: #e8f2ff; }

    .page-title { font-size: 24px; font-weight: 800; color: var(--text-dark); }

    /* Stats */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--white);
        border-radius: 14px;
        padding: 20px 24px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }

    .stat-label { font-size: 13px; color: var(--text-gray); margin-bottom: 6px; }
    .stat-value { font-size: 22px; font-weight: 800; color: var(--text-dark); }
    .stat-value.blue { color: var(--primary); }

    /* Table card */
    .table-card {
        background: var(--white);
        border-radius: 16px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        overflow: hidden;
    }

    .table-card table { width: 100%; border-collapse: collapse; font-size: 14px; }

    .table-card thead tr { background: #f8fafc; }

    .table-card th {
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-gray);
        text-transform: uppercase;
        letter-spacing: .5px;
        border-bottom: 2px solid var(--border);
        text-align: left;
    }

    .table-card td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        color: var(--text-dark);
        vertical-align: middle;
    }

    .table-card tbody tr:last-child td { border-bottom: none; }
    .table-card tbody tr:hover { background: #f8fafc; }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-paid     { background: #d1fae5; color: #065f46; }
    .badge-vnpay    { background: #dbeafe; color: #1e40af; }
    .badge-vietqr   { background: #d1fae5; color: #065f46; }
    .badge-momo     { background: #fdf0f7; color: #ae2070; }
    .badge-other    { background: #f1f5f9; color: #475569; }
    .badge-completed { background: #ede9fe; color: #6d28d9; }
    .badge-upcoming  { background: #dbeafe; color: #1e40af; }
    .badge-ongoing   { background: #d1fae5; color: #065f46; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }

    .tour-name { font-weight: 600; color: var(--text-dark); }
    .tour-meta { font-size: 12px; color: var(--text-gray); margin-top: 2px; }

    .amount { font-weight: 700; color: var(--primary); }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid var(--border);
        transition: background .2s;
    }
    .btn-detail:hover { background: #e2e8f0; color: var(--text-dark); }

    .empty-state {
        text-align: center;
        padding: 60px 24px;
        color: var(--text-gray);
    }
    .empty-state .icon { font-size: 48px; margin-bottom: 12px; opacity: .4; }

    @media (max-width: 640px) {
        .stats-row { grid-template-columns: 1fr; }
        .table-card { overflow-x: auto; }
    }
</style>
@endsection

@section('content')
<div class="history-page">
    <div class="container">

        <div class="page-head">
            <a href="{{ route('user.profile') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Hồ sơ
            </a>
            <h1 class="page-title">Lịch sử thanh toán</h1>
        </div>

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-label">Tổng giao dịch</div>
                <div class="stat-value">{{ $payments->total() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Tổng chi tiêu</div>
                <div class="stat-value blue">{{ number_format($payments->sum('total_price'), 0, ',', '.') }} ₫</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Giao dịch gần nhất</div>
                <div class="stat-value" style="font-size:16px;">
                    {{ $payments->first()?->updated_at?->format('d/m/Y') ?? '—' }}
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-card">
            @if($payments->isEmpty())
                <div class="empty-state">
                    <div class="icon">💳</div>
                    <div style="font-size:15px; font-weight:600;">Chưa có giao dịch nào</div>
                    <div style="font-size:13px; margin-top:6px;">Các giao dịch thanh toán tour sẽ hiển thị tại đây.</div>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Tour</th>
                            <th>Ngày thanh toán</th>
                            <th>Phương thức</th>
                            <th>Số tiền</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $booking)
                        <tr>
                            <td><span style="font-weight:700;">#{{ $booking->booking_id }}</span></td>
                            <td>
                                <div class="tour-name">{{ $booking->tour->name ?? 'Tour không xác định' }}</div>
                                <div class="tour-meta">
                                    {{ $booking->num_people }} người
                                    @if($booking->schedule?->start_date)
                                        · {{ $booking->schedule->start_date->format('d/m/Y') }}
                                    @endif
                                </div>
                            </td>
                            <td style="color:var(--text-gray);">{{ $booking->updated_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td>
                                @php $method = $booking->payment_method; @endphp
                                @if($method === 'vnpay')
                                    <span class="badge badge-vnpay">💳 VNPAY</span>
                                @elseif($method === 'vietqr')
                                    <span class="badge badge-vietqr">📱 VietQR</span>
                                @elseif($method === 'momo')
                                    <span class="badge badge-momo">💜 MoMo</span>
                                @else
                                    <span class="badge badge-other">{{ $method ? strtoupper($method) : '—' }}</span>
                                @endif
                            </td>
                            <td><span class="amount">{{ number_format($booking->total_price, 0, ',', '.') }} ₫</span></td>
                            <td>
                                @php
                                    $statusMap = ['completed'=>'badge-completed','upcoming'=>'badge-upcoming','ongoing'=>'badge-ongoing','cancelled'=>'badge-cancelled'];
                                    $statusLabel = \App\Models\Booking::STATUS[$booking->status] ?? $booking->status;
                                @endphp
                                <span class="badge {{ $statusMap[$booking->status] ?? 'badge-upcoming' }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <a href="{{ route('user.booking.detail', $booking->booking_id) }}" class="btn-detail">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($payments->hasPages())
                    <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
                        {{ $payments->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection
