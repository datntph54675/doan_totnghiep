@extends('layouts.app')

@section('title', 'Bảng điều khiển Admin')

@section('css')
<style>
    /* ── Page background ─────────────────────────────── */
    .content-page {
        background: #f1f5f9;
    }

    /* ── Stat cards (top 4) ──────────────────────────── */
    .stats-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.07);
        background: #fff;
        transition: box-shadow .2s, transform .2s;
    }

    .stats-card:hover {
        box-shadow: 0 8px 28px rgba(15, 23, 42, 0.13);
        transform: translateY(-2px);
    }

    .stats-card.theme-blue {
        border-left: 4px solid #2563eb;
    }

    .stats-card.theme-green {
        border-left: 4px solid #059669;
    }

    .stats-card.theme-violet {
        border-left: 4px solid #7c3aed;
    }

    .stats-card.theme-orange {
        border-left: 4px solid #d97706;
    }

    .icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .icon-wrap.theme-blue {
        background: rgba(37, 99, 235, .11);
        color: #2563eb;
    }

    .icon-wrap.theme-green {
        background: rgba(5, 150, 105, .11);
        color: #059669;
    }

    .icon-wrap.theme-violet {
        background: rgba(124, 58, 237, .11);
        color: #7c3aed;
    }

    .icon-wrap.theme-orange {
        background: rgba(217, 119, 6, .11);
        color: #d97706;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -.03em;
        line-height: 1.1;
    }

    /* ── Trend badge ─────────────────────────────────── */
    .trend-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 999px;
    }

    .trend-badge.up {
        background: rgba(5, 150, 105, .1);
        color: #059669;
    }

    .trend-badge.down {
        background: rgba(220, 38, 38, .1);
        color: #dc2626;
    }

    .trend-badge.flat {
        background: rgba(71, 85, 105, .1);
        color: #475569;
    }

    /* ── Panel cards (charts / tables) ───────────────── */
    .stats-panel {
        border: 0;
        border-radius: 18px;
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.07);
        background: #fff;
    }

    .stats-kicker {
        letter-spacing: .08em;
        text-transform: uppercase;
        font-size: .72rem;
        font-weight: 700;
    }

    /* ── Occupancy bar ───────────────────────────────── */
    .mini-progress {
        height: 8px;
        border-radius: 999px;
        background: #e2e8f0;
        overflow: hidden;
    }

    .mini-progress-bar {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #0f766e, #14b8a6);
        transition: width .6s ease;
    }

    /* ── Quick metric boxes ──────────────────────────── */
    .metric-box {
        border-radius: 12px;
        padding: 0.85rem 1rem;
    }

    .metric-box.mb-blue {
        background: #eff6ff;
    }

    .metric-box.mb-orange {
        background: #fff7ed;
    }

    .metric-box.mb-green {
        background: #ecfdf5;
    }

    .metric-box.mb-violet {
        background: #faf5ff;
    }

    .metric-box .metric-label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #64748b;
        margin-bottom: .3rem;
    }

    .metric-box .metric-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -.02em;
        line-height: 1.1;
    }

    /* ── Top tours ranking ───────────────────────────── */
    .ranking-row {
        padding: .7rem 0;
    }

    .ranking-row+.ranking-row {
        border-top: 1px solid #f1f5f9;
    }

    .rank-badge {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        font-size: .75rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .rank-1 {
        background: #fef3c7;
        color: #b45309;
    }

    .rank-2 {
        background: #f1f5f9;
        color: #475569;
    }

    .rank-3 {
        background: #fff7ed;
        color: #c2410c;
    }

    .rank-n {
        background: #f8fafc;
        color: #94a3b8;
    }

    /* ── Table ───────────────────────────────────────── */
    .dashboard-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: .74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        border-bottom: 1px solid #e2e8f0;
        padding-top: .7rem;
        padding-bottom: .7rem;
    }

    .dashboard-table tbody tr {
        transition: background .12s;
    }

    .dashboard-table tbody tr:hover {
        background: #f8fafc;
    }

    .dashboard-table tbody td {
        border-color: #f1f5f9;
        vertical-align: middle;
    }

    /* ── Status badges ───────────────────────────────── */
    .pay-badge {
        font-size: .72rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 999px;
        letter-spacing: .02em;
    }

    .pay-paid {
        background: rgba(5, 150, 105, .12);
        color: #059669;
    }

    .pay-unpaid {
        background: rgba(71, 85, 105, .1);
        color: #475569;
    }

    .pay-refunded {
        background: rgba(220, 38, 38, .1);
        color: #dc2626;
    }

    .pay-deposit {
        background: rgba(217, 119, 6, .12);
        color: #b45309;
    }

    /* ── Filter bar ──────────────────────────────────── */
    .filter-bar {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.06);
        padding: .8rem 1.2rem;
    }

    .preset-btn {
        border: 1px solid #e2e8f0;
        background: transparent;
        border-radius: 7px;
        padding: .3rem .8rem;
        font-size: .8rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: background .14s, border-color .14s, color .14s;
        line-height: 1.5;
        white-space: nowrap;
    }

    .preset-btn:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #2563eb;
    }

    .preset-btn.active {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
    }

    /* ── Revenue header block ────────────────────────── */
    .revenue-header-block {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #3b82f6 100%);
        border-radius: 16px;
        padding: 1rem 1.4rem;
        color: #fff;
        min-width: 220px;
        text-align: right;
    }

    .revenue-header-block .rev-label {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        opacity: .8;
    }

    .revenue-header-block .rev-amount {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -.03em;
        line-height: 1.15;
    }

    .revenue-header-block .rev-trend {
        font-size: .8rem;
        opacity: .9;
    }

    /* ── Chart tab switcher ──────────────────────────── */
    .chart-tabs {
        display: flex;
        gap: 3px;
        background: #f1f5f9;
        border-radius: 9px;
        padding: 3px;
    }

    .chart-tab {
        border: none;
        background: transparent;
        border-radius: 6px;
        padding: 5px 16px;
        font-size: .8rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        white-space: nowrap;
        transition: background .14s, color .14s, box-shadow .14s;
    }

    .chart-tab:hover {
        color: #2563eb;
    }

    .chart-tab.active {
        background: #fff;
        color: #2563eb;
        box-shadow: 0 1px 6px rgba(15, 23, 42, .1);
    }

    /* ── Revenue summary chips ──────────────────────── */
    .rev-summary-chip {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: .4rem .9rem;
        font-size: .8rem;
        line-height: 1.3;
    }

    .rev-summary-chip .chip-label {
        color: #64748b;
    }

    .rev-summary-chip .chip-value {
        font-weight: 700;
        color: #0f172a;
    }
</style>
@endsection

@section('content')
@php
$overviewCards = [
[
'label' => 'Tổng user',
'value' => number_format($stats['totalUsers']),
'sub' => 'Toàn hệ thống',
'valueClass' => 'stat-number',
'icon' => 'fas fa-user-shield',
'theme' => 'theme-blue',
],
[
'label' => 'Tổng tour',
'value' => number_format($stats['totalTours']),
'sub' => number_format($stats['activeTours']) . ' tour đang mở bán',
'valueClass' => 'stat-number',
'icon' => 'fas fa-map-marked-alt',
'theme' => 'theme-violet',
],
[
'label' => 'Tổng booking',
'value' => number_format($stats['totalBookings']),
'sub' => number_format($stats['paidBookings']) . ' booking đã thanh toán trong kỳ',
'valueClass' => 'stat-number',
'icon' => 'fas fa-ticket-alt',
'theme' => 'theme-green',
],
[
'label' => 'Tổng doanh thu',
'value' => number_format($stats['totalRevenue'], 0, ',', '.') . ' đ',
'sub' => 'Tính theo booking đã thanh toán',
'valueClass' => 'stat-number',
'icon' => 'fas fa-sack-dollar',
'theme' => 'theme-orange',
],
[
'label' => 'Booking hôm nay',
'value' => number_format($stats['bookingsToday']),
'sub' => 'Phát sinh trong ngày',
'valueClass' => 'stat-number',
'icon' => 'fas fa-calendar-day',
'theme' => 'theme-blue',
],
[
'label' => 'Tour hot nhất',
'value' => $stats['hotTourName'] ?: 'Chưa có dữ liệu',
'sub' => number_format($stats['hotTourBookingCount']) . ' booking | ' . number_format((float) $stats['hotTourRevenue'], 0, ',', '.') . ' đ',
'valueClass' => 'fs-5 fw-bold lh-sm text-truncate d-block',
'icon' => 'fas fa-fire',
'theme' => 'theme-green',
],
[
'label' => 'Tour sắp khởi hành',
'value' => number_format($stats['upcomingSchedulesCount']),
'sub' => $stats['nextScheduleDate'] ? 'Gần nhất: ' . $stats['nextScheduleDate']->format('d/m/Y') : 'Không có lịch sắp tới',
'valueClass' => 'stat-number',
'icon' => 'fas fa-plane-departure',
'theme' => 'theme-violet',
],
[
'label' => 'HDV đang hoạt động',
'value' => number_format($stats['activeGuidesCount']),
'sub' => number_format($stats['ongoingTourCount']) . ' tour đang diễn ra',
'valueClass' => 'stat-number',
'icon' => 'fas fa-hiking',
'theme' => 'theme-orange',
],
[
'label' => 'Tỷ lệ hủy booking',
'value' => number_format($stats['cancellationRate'], 1) . '%',
'sub' => number_format($stats['periodCancelledBookings']) . ' booking đã hủy trong kỳ',
'valueClass' => 'stat-number ' . ($stats['cancellationRate'] >= 20 ? 'text-danger' : ($stats['cancellationRate'] >= 10 ? 'text-warning' : 'text-success')),
'icon' => 'fas fa-ban',
'theme' => $stats['cancellationRate'] >= 20 ? 'theme-orange' : 'theme-green',
],
];
@endphp

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Bảng điều khiển</h2>
        <p class="text-muted mb-0">Chào mừng trở lại,
            <span class="fw-semibold text-primary">{{ auth()->user()->fullname ?? auth()->user()->username }}</span>!
        </p>
    </div>
    <div class="revenue-header-block">
        <div class="rev-label mb-1">Doanh thu {{ $filterLabel }}</div>
        <div class="rev-amount">{{ number_format($stats['monthlyRevenue'], 0, ',', '.') }} đ</div>
        <div class="rev-trend mt-1">
            @if($stats['monthlyRevenueTrend'] === null)
            Chưa có dữ liệu so sánh
            @elseif($stats['monthlyRevenueTrend'] >= 0)
            <i class="fas fa-arrow-up fa-xs me-1"></i>+{{ number_format($stats['monthlyRevenueTrend'], 1) }}% so với kỳ trước
            @else
            <i class="fas fa-arrow-down fa-xs me-1"></i>{{ number_format($stats['monthlyRevenueTrend'], 1) }}% so với kỳ trước
            @endif
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar mb-4">
    <form method="GET" action="{{ route('admin.dashboard') }}" id="filterForm">
        <input type="hidden" name="preset" id="presetInput" value="{{ $preset }}">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <span class="text-muted fw-semibold small me-1">Lọc theo:</span>
            @foreach([
            'today' => 'Hôm nay',
            '7d' => '7 ngày',
            '30d' => '30 ngày',
            'this_month' => 'Tháng này',
            'last_month' => 'Tháng trước',
            'this_quarter' => 'Quý này',
            'this_year' => 'Năm này',
            'custom' => 'Tùy chỉnh',
            ] as $key => $label)
            <button type="button" class="preset-btn {{ $preset === $key ? 'active' : '' }}"
                data-preset="{{ $key }}">{{ $label }}</button>
            @endforeach
            <div class="align-items-center gap-2 {{ $preset === 'custom' ? 'd-flex' : 'd-none' }}"
                id="customRangeGroup">
                <input type="date" name="date_from" class="form-control form-control-sm"
                    style="width: 150px;"
                    value="{{ $preset === 'custom' ? $filterFrom->format('Y-m-d') : '' }}">
                <span class="text-muted">–</span>
                <input type="date" name="date_to" class="form-control form-control-sm"
                    style="width: 150px;"
                    value="{{ $preset === 'custom' ? $filterTo->format('Y-m-d') : '' }}">
                <button type="submit" class="btn btn-primary btn-sm px-3">Áp dụng</button>
            </div>
        </div>
    </form>
</div>

<div class="row g-3 mb-4" id="tong-quan">
    @foreach($overviewCards as $card)
    <div class="col-sm-6 col-xl-4">
        <div class="card stats-card h-100 {{ $card['theme'] }}">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div class="flex-grow-1 min-width-0">
                        <div class="stats-kicker text-muted mb-2">{{ $card['label'] }}</div>
                        <div class="{{ $card['valueClass'] ?? 'stat-number' }} text-dark mb-1">{{ $card['value'] }}</div>
                        <div class="small text-muted">{{ $card['sub'] }}</div>
                    </div>
                    <div class="icon-wrap {{ $card['theme'] }}">
                        <i class="{{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Revenue chart with 4-tab switcher --}}
<div class="card stats-panel mb-4" id="doanh-thu">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-1">
            <div>
                <div class="stats-kicker text-primary mb-1">Doanh thu</div>
                <h4 class="mb-0" id="revChartTitle">Doanh thu theo tháng — năm {{ $monthlyChartYear }}</h4>
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="d-flex gap-3 flex-wrap">
                    <div class="rev-summary-chip">
                        <div class="chip-label">Tổng hiển thị</div>
                        <div class="chip-value" id="revChartTotal">...</div>
                    </div>
                    <div class="rev-summary-chip">
                        <div class="chip-label">Cao nhất</div>
                        <div class="chip-value" id="revChartPeak">...</div>
                    </div>
                </div>
                <div class="chart-tabs" id="revChartTabs">
                    <button class="chart-tab" data-tab="day">Ngày</button>
                    <button class="chart-tab" data-tab="week">Tuần</button>
                    <button class="chart-tab active" data-tab="month">Tháng</button>
                    <button class="chart-tab" data-tab="year">Năm</button>
                </div>
            </div>
        </div>
        <div id="revenueChart" style="min-height: 380px;"></div>
    </div>
</div>

{{-- Booking chart --}}
<div class="card stats-panel mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="stats-kicker text-primary mb-1">Booking theo tháng</div>
                <h4 class="mb-0">Số booking năm {{ $monthlyChartYear }}</h4>
            </div>
        </div>
        <div id="bookingMonthlyChart" style="min-height: 300px;"></div>
    </div>
</div>

<div class="card stats-panel">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="stats-kicker text-secondary mb-1">Booking gần đây</div>
                <h4 class="mb-0">Hoạt động mới nhất</h4>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary btn-sm px-3">Xem tất cả</a>
        </div>
        <div class="table-responsive">
            <table class="table dashboard-table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:100px">Mã booking</th>
                        <th>Khách hàng</th>
                        <th>Tour</th>
                        <th style="width:130px">Ngày đặt</th>
                        <th style="width:140px">Thanh toán</th>
                        <th class="text-end" style="width:140px">Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                    <tr>
                        <td>
                            <a href="{{ route('admin.bookings.show', $booking->booking_id) }}"
                                class="fw-bold text-dark text-decoration-none">#{{ $booking->booking_id }}</a>
                        </td>
                        <td class="fw-medium">{{ $booking->customer->fullname ?? 'N/A' }}</td>
                        <td class="text-muted text-truncate" style="max-width:200px">{{ $booking->tour->name ?? 'N/A' }}</td>
                        <td class="text-muted small">{{ optional($booking->booking_date)->format('d/m/Y H:i') ?? 'N/A' }}</td>
                        <td>
                            @php
                            $ps = $booking->payment_status;
                            $psCls = match($ps) {
                            'paid' => 'pay-paid',
                            'refunded' => 'pay-refunded',
                            'deposit' => 'pay-deposit',
                            default => 'pay-unpaid',
                            };
                            @endphp
                            <span class="pay-badge {{ $psCls }}">
                                {{ \App\Models\Booking::PAYMENT_STATUS[$ps] ?? ucfirst($ps) }}
                            </span>
                        </td>
                        <td class="text-end fw-bold">{{ number_format((float) $booking->total_price, 0, ',', '.') }} đ</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                            Chưa có booking nào trong kỳ này
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
@php
$_chartJson = json_encode(['revenueDay'=>$revenueDay,'revenueWeek'=>$revenueWeek,'revenueMonth'=>$revenueMonth,'revenueYear'=>$revenueYear,'monthlyChartLabels'=>$monthlyChartLabels,'monthlyBookingSeries'=>$monthlyBookingSeries],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
@endphp
<script>
    const _d = {!! $_chartJson !!};

    const revenueDatasets = {
        day: _d.revenueDay ?? {
            labels: [],
            data: []
        },
        week: _d.revenueWeek ?? {
            labels: [],
            data: []
        },
        month: _d.revenueMonth ?? {
            labels: [],
            data: []
        },
        year: _d.revenueYear ?? {
            labels: [],
            data: []
        },
    };

    const monthlyChartLabels = _d.monthlyChartLabels ?? [];
    const monthlyBookingSeries = _d.monthlyBookingSeries ?? [];

    const tabTitles = {
        day: '30 ngày gần nhất',
        week: '12 tuần gần nhất',
        month: 'Doanh thu theo tháng — năm {{ $monthlyChartYear }}',
        year: 'Doanh thu theo năm (5 năm gần nhất)',
    };

    // ── Filter bar ──────────────────────────────────────────────────────
    const filterForm = document.querySelector('#filterForm');
    const presetInput = document.querySelector('#presetInput');
    const customRangeGroup = document.querySelector('#customRangeGroup');

    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const p = btn.dataset.preset;
            document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            presetInput.value = p;
            if (p === 'custom') {
                customRangeGroup.classList.remove('d-none');
                customRangeGroup.classList.add('d-flex');
            } else {
                customRangeGroup.classList.add('d-none');
                customRangeGroup.classList.remove('d-flex');
                filterForm.submit();
            }
        });
    });

    // ── Helpers ─────────────────────────────────────────────────────────
    const fmtVND = v => new Intl.NumberFormat('vi-VN').format(v);
    const fmtShort = v => {
        if (v >= 1e9) return (v / 1e9).toFixed(1).replace(/\.0$/, '') + ' tỷ';
        if (v >= 1e6) return (v / 1e6).toFixed(1).replace(/\.0$/, '') + ' triệu';
        if (v >= 1e3) return (v / 1e3).toFixed(1).replace(/\.0$/, '') + ' ngàn';
        return fmtVND(v);
    };

    const chartDefaults = {
        fontFamily: 'inherit',
        toolbar: {
            show: false
        },
        animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 500
        },
    };
    const gridStyle = {
        borderColor: '#f1f5f9',
        strokeDashArray: 4,
        xaxis: {
            lines: {
                show: false
            }
        },
    };

    function updateSummaryChips(data) {
        const total = data.reduce((a, b) => a + b, 0);
        const peak = Math.max(...data, 0);
        document.querySelector('#revChartTotal').textContent = fmtShort(total) + ' đ';
        document.querySelector('#revChartPeak').textContent = fmtShort(peak) + ' đ';
    }

    // ── Revenue chart — 4 tabs ──────────────────────────────────────────
    let activeTab = 'month';

    function makeRevenueOptions(labels, data) {
        return {
            chart: {
                ...chartDefaults,
                id: 'revenueChart',
                height: 380,
                type: 'bar'
            },
            series: [{
                name: 'Doanh thu',
                data
            }],
            labels,
            colors: ['#2563eb'],
            plotOptions: {
                bar: {
                    borderRadius: 5,
                    columnWidth: activeTab === 'year' ? '38%' : '52%'
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.12,
                    gradientToColors: ['#60a5fa'],
                    stops: [0, 100]
                }
            },
            stroke: {
                width: 2,
                colors: ['transparent']
            },
            dataLabels: {
                enabled: false
            },
            grid: gridStyle,
            xaxis: {
                categories: labels,
                labels: {
                    style: {
                        colors: '#94a3b8',
                        fontSize: '12px'
                    },
                    rotate: activeTab === 'day' ? -40 : 0,
                    rotateAlways: activeTab === 'day',
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#94a3b8',
                        fontSize: '11px'
                    },
                    formatter: v => fmtShort(v)
                },
            },
            tooltip: {
                y: {
                    formatter: v => fmtVND(v) + ' đ'
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                fontSize: '13px'
            },
        };
    }

    const initDs = revenueDatasets.month;
    updateSummaryChips(initDs.data);
    const revenueChart = new ApexCharts(document.querySelector('#revenueChart'), makeRevenueOptions(initDs.labels, initDs.data));
    revenueChart.render();

    document.querySelectorAll('#revChartTabs .chart-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.tab;
            if (tab === activeTab) return;
            activeTab = tab;

            document.querySelectorAll('#revChartTabs .chart-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const ds = revenueDatasets[tab];
            document.querySelector('#revChartTitle').textContent = tabTitles[tab];
            updateSummaryChips(ds.data);

            revenueChart.updateOptions({
                labels: ds.labels,
                xaxis: {
                    categories: ds.labels,
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '12px'
                        },
                        rotate: tab === 'day' ? -40 : 0,
                        rotateAlways: tab === 'day',
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                },
                plotOptions: {
                    bar: {
                        borderRadius: 5,
                        columnWidth: tab === 'year' ? '38%' : '52%'
                    }
                },
                series: [{
                    name: 'Doanh thu',
                    data: ds.data
                }],
            }, true, true);
        });
    });

    // ── Booking by month (line chart) ───────────────────────────────────
    new ApexCharts(document.querySelector('#bookingMonthlyChart'), {
        chart: {
            ...chartDefaults,
            type: 'area',
            height: 300
        },
        series: [{
            name: 'Booking',
            data: monthlyBookingSeries
        }],
        labels: monthlyChartLabels,
        colors: ['#059669'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 4,
            strokeWidth: 0
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.2,
                gradientToColors: ['#34d399'],
                opacityFrom: 0.35,
                opacityTo: 0,
                stops: [0, 100]
            }
        },
        dataLabels: {
            enabled: false
        },
        grid: gridStyle,
        xaxis: {
            categories: monthlyChartLabels,
            labels: {
                style: {
                    colors: '#94a3b8',
                    fontSize: '12px'
                }
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
        },
        yaxis: {
            min: 0,
            labels: {
                style: {
                    colors: '#94a3b8'
                }
            },
        },
        tooltip: {
            y: {
                formatter: v => v + ' booking'
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            fontSize: '13px'
        },
    }).render();
</script>
@endsection