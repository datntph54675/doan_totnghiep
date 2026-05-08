<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\DepartureSchedule;
use App\Models\Guide;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user instanceof User && $user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }

            Auth::logout();
            return back()->withErrors(['username' => 'Tài khoản không có quyền admin.']);
        }

        return back()->withErrors(['username' => 'Tên đăng nhập hoặc mật khẩu không đúng.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    public function dashboard(Request $request)
    {
        $today  = now();
        $preset = $request->query('preset', 'this_month');

        $totalUsers    = User::count();
        $totalTours    = Tour::count();
        $totalBookings = Booking::count();
        $totalRevenue  = (float) Booking::query()
            ->where('payment_status', 'paid')
            ->sum('total_price');
        $bookingsToday = Booking::query()
            ->whereDate('booking_date', $today->toDateString())
            ->count();

        $hotTour = Tour::query()
            ->leftJoin('booking', function ($join) {
                $join->on('booking.tour_id', '=', 'tours.tour_id')
                    ->where('booking.payment_status', 'paid');
            })
            ->groupBy('tours.tour_id', 'tours.name')
            ->orderByDesc(DB::raw('COALESCE(SUM(booking.total_price), 0)'))
            ->orderByDesc(DB::raw('COUNT(booking.booking_id)'))
            ->select([
                'tours.tour_id',
                'tours.name',
                DB::raw('COALESCE(SUM(booking.total_price), 0) as revenue'),
                DB::raw('COUNT(booking.booking_id) as booking_count'),
            ])
            ->first();

        // ── Resolve filter date range ──────────────────────────────────────
        switch ($preset) {
            case 'today':
                $filterFrom  = $today->copy()->startOfDay();
                $filterTo    = $today->copy();
                $filterLabel = 'Hôm nay (' . $today->format('d/m/Y') . ')';
                break;
            case '7d':
                $filterFrom  = $today->copy()->subDays(6)->startOfDay();
                $filterTo    = $today->copy();
                $filterLabel = '7 ngày qua';
                break;
            case '30d':
                $filterFrom  = $today->copy()->subDays(29)->startOfDay();
                $filterTo    = $today->copy();
                $filterLabel = '30 ngày qua';
                break;
            case 'last_month':
                $filterFrom  = $today->copy()->subMonthNoOverflow()->startOfMonth();
                $filterTo    = $filterFrom->copy()->endOfMonth();
                $filterLabel = 'Tháng ' . $filterFrom->format('m/Y');
                break;
            case 'this_quarter':
                $filterFrom  = $today->copy()->startOfQuarter();
                $filterTo    = $today->copy();
                $filterLabel = 'Quý ' . (int) ceil($today->month / 3) . '/' . $today->year;
                break;
            case 'this_year':
                $filterFrom  = $today->copy()->startOfYear();
                $filterTo    = $today->copy();
                $filterLabel = 'Năm ' . $today->year;
                break;
            case 'custom':
                try {
                    $filterFrom = $request->filled('date_from')
                        ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->query('date_from'))->startOfDay()
                        : $today->copy()->startOfMonth();
                    $filterTo   = $request->filled('date_to')
                        ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->query('date_to'))->endOfDay()
                        : $today->copy();
                    if ($filterFrom->gt($filterTo)) {
                        [$filterFrom, $filterTo] = [$filterTo, $filterFrom];
                    }
                } catch (\Throwable $e) {
                    $filterFrom = $today->copy()->startOfMonth();
                    $filterTo   = $today->copy();
                }
                $filterLabel = $filterFrom->format('d/m/Y') . ' – ' . $filterTo->format('d/m/Y');
                break;
            default:
                $preset      = 'this_month';
                $filterFrom  = $today->copy()->startOfMonth();
                $filterTo    = $today->copy();
                $filterLabel = 'Tháng ' . $today->format('m/Y');
        }

        // ── Comparison period (same length, immediately before filterFrom) ─
        $periodSeconds = max(1, (int) $filterFrom->diffInSeconds($filterTo));
        $compareFrom   = $filterFrom->copy()->subSeconds($periodSeconds + 1)->startOfDay();
        $compareTo     = $filterFrom->copy()->subSecond();

        // ── Chart grouping: day-level ≤ 60 days, month-level otherwise ────
        $totalDays  = max(1, (int) $filterFrom->diffInDays($filterTo) + 1);
        $groupByDay = $totalDays <= 60;

        // ── Upcoming schedules (always all-time) ───────────────────────────
        $upcomingSchedulesQuery = DepartureSchedule::query()
            ->where('status', DepartureSchedule::STATUS_SCHEDULED)
            ->whereDate('start_date', '>=', today());

        $capacityTotals = (clone $upcomingSchedulesQuery)
            ->selectRaw('COALESCE(SUM(max_people), 0) as total_capacity')
            ->selectRaw('COALESCE(SUM(GREATEST(max_people - available_spots, 0)), 0) as booked_capacity')
            ->first();

        // ── Period-scoped revenue & bookings ──────────────────────────────
        $periodRevenue = (float) Booking::query()
            ->where('payment_status', 'paid')
            ->whereBetween('booking_date', [$filterFrom, $filterTo])
            ->sum('total_price');

        $compareRevenue = (float) Booking::query()
            ->where('payment_status', 'paid')
            ->whereBetween('booking_date', [$compareFrom, $compareTo])
            ->sum('total_price');

        $periodBookings = Booking::query()
            ->whereBetween('booking_date', [$filterFrom, $filterTo])
            ->count();

        $compareBookings = Booking::query()
            ->whereBetween('booking_date', [$compareFrom, $compareTo])
            ->count();

        // ── Chart series ──────────────────────────────────────────────────
        if ($groupByDay) {
            $chartRaw = Booking::query()
                ->selectRaw('DATE(booking_date) as period_key')
                ->selectRaw('COUNT(*) as bookings_count')
                ->selectRaw("COALESCE(SUM(CASE WHEN payment_status = 'paid' THEN total_price ELSE 0 END), 0) as revenue_total")
                ->whereBetween('booking_date', [$filterFrom, $filterTo])
                ->groupBy('period_key')
                ->orderBy('period_key')
                ->get()
                ->keyBy('period_key');

            $chartLabels        = [];
            $bookingTrendSeries = [];
            $revenueTrendSeries = [];

            for ($cursor = $filterFrom->copy()->startOfDay(); $cursor->lte($filterTo); $cursor->addDay()) {
                $key                  = $cursor->format('Y-m-d');
                $chartLabels[]        = $cursor->format('d/m');
                $bookingTrendSeries[] = (int) data_get($chartRaw->get($key), 'bookings_count', 0);
                $revenueTrendSeries[] = round((float) data_get($chartRaw->get($key), 'revenue_total', 0), 2);
            }
        } else {
            $chartRaw = Booking::query()
                ->selectRaw("DATE_FORMAT(booking_date, '%Y-%m') as period_key")
                ->selectRaw('COUNT(*) as bookings_count')
                ->selectRaw("COALESCE(SUM(CASE WHEN payment_status = 'paid' THEN total_price ELSE 0 END), 0) as revenue_total")
                ->whereBetween('booking_date', [$filterFrom, $filterTo])
                ->groupBy('period_key')
                ->orderBy('period_key')
                ->get()
                ->keyBy('period_key');

            $chartLabels        = [];
            $bookingTrendSeries = [];
            $revenueTrendSeries = [];

            for ($cursor = $filterFrom->copy()->startOfMonth(); $cursor->lte($filterTo); $cursor->addMonth()) {
                $key                  = $cursor->format('Y-m');
                $chartLabels[]        = 'Th' . $cursor->format('m/Y');
                $bookingTrendSeries[] = (int) data_get($chartRaw->get($key), 'bookings_count', 0);
                $revenueTrendSeries[] = round((float) data_get($chartRaw->get($key), 'revenue_total', 0), 2);
            }
        }

        // ── Status distributions within period ────────────────────────────
        $bookingStatusCounts = Booking::query()
            ->whereBetween('booking_date', [$filterFrom, $filterTo])
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $paymentStatusCounts = Booking::query()
            ->whereBetween('booking_date', [$filterFrom, $filterTo])
            ->select('payment_status', DB::raw('COUNT(*) as total'))
            ->groupBy('payment_status')
            ->pluck('total', 'payment_status');

        $paymentStatusLabels = [
            'unpaid'   => 'Chưa thanh toán',
            'deposit'  => 'Đặt cọc',
            'paid'     => 'Đã thanh toán',
            'refunded' => 'Đã hoàn tiền',
        ];

        $totalCapacity  = (int) ($capacityTotals->total_capacity ?? 0);
        $bookedCapacity = (int) ($capacityTotals->booked_capacity ?? 0);

        // ── Tour sắp khởi hành ──────────────────────────────────────────────────
        $upcomingSchedulesCount = DepartureSchedule::query()
            ->where('status', DepartureSchedule::STATUS_SCHEDULED)
            ->whereDate('start_date', '>=', today())
            ->count();

        $nextSchedule = DepartureSchedule::query()
            ->where('status', DepartureSchedule::STATUS_SCHEDULED)
            ->whereDate('start_date', '>=', today())
            ->orderBy('start_date')
            ->first(['start_date', 'tour_id']);

        // ── Hướng dẫn viên đang dẫn tour ──────────────────────────────────
        $activeGuidesCount = DepartureSchedule::query()
            ->where('status', DepartureSchedule::STATUS_ONGOING)
            ->whereNotNull('guide_id')
            ->distinct('guide_id')
            ->count('guide_id');

        $ongoingTourCount = DepartureSchedule::query()
            ->where('status', DepartureSchedule::STATUS_ONGOING)
            ->count();

        // ── Tỷ lệ hủy booking trong kỳ ────────────────────────────────
        $periodCancelledBookings = Booking::query()
            ->whereBetween('booking_date', [$filterFrom, $filterTo])
            ->where('status', 'cancelled')
            ->count();

        $cancellationRate = $periodBookings > 0
            ? round(($periodCancelledBookings / $periodBookings) * 100, 1)
            : 0.0;

        $stats = [
            // Required overview KPIs
            'totalUsers'               => $totalUsers,
            'totalTours'               => $totalTours,
            'totalBookings'            => $totalBookings,
            'totalRevenue'             => $totalRevenue,
            'bookingsToday'            => $bookingsToday,
            'hotTourName'              => $hotTour?->name,
            'hotTourRevenue'           => (float) ($hotTour->revenue ?? 0),
            'hotTourBookingCount'      => (int) ($hotTour->booking_count ?? 0),
            // All-time counts
            'activeTours'             => Tour::where('status', 'active')->count(),
            'totalCustomers'          => Customer::count(),
            'totalGuides'             => Guide::count(),
            'upcomingSchedulesCount'  => $upcomingSchedulesCount,
            'nextScheduleDate'        => $nextSchedule?->start_date,
            'activeGuidesCount'       => $activeGuidesCount,
            'ongoingTourCount'        => $ongoingTourCount,
            'cancellationRate'        => $cancellationRate,
            'periodCancelledBookings' => $periodCancelledBookings,
            // All-time action items
            'pendingConfirmationCount' => Booking::where('payment_status', 'paid')
                ->where('status', '!=', 'cancelled')
                ->where('admin_confirmed', false)
                ->count(),
            'pendingRefundCount' => Booking::where('status', 'cancelled')
                ->where('payment_status', 'paid')
                ->count(),
            'occupancyRate' => $totalCapacity > 0 ? round(($bookedCapacity / $totalCapacity) * 100, 1) : 0,
            // Period-scoped (kept under legacy keys for view compatibility)
            'monthlyRevenue'               => $periodRevenue,
            'monthlyRevenueTrend'          => $this->calculateTrend($periodRevenue, $compareRevenue),
            'monthlyBookings'              => $periodBookings,
            'monthlyBookingsTrend'         => $this->calculateTrend((float) $periodBookings, (float) $compareBookings),
            'paidBookings'                 => Booking::whereBetween('booking_date', [$filterFrom, $filterTo])
                ->where('payment_status', 'paid')->count(),
            'customersWithBookingsInPeriod' => Booking::query()
                ->whereBetween('booking_date', [$filterFrom, $filterTo])
                ->distinct('customer_id')
                ->count('customer_id'),
        ];

        $bookingStatusChart = [
            'labels' => array_values(Booking::STATUS),
            'series' => collect(array_keys(Booking::STATUS))
                ->map(fn(string $status) => (int) ($bookingStatusCounts[$status] ?? 0))
                ->values()
                ->all(),
        ];

        $paymentStatusChart = [
            'labels' => array_values($paymentStatusLabels),
            'series' => collect(array_keys($paymentStatusLabels))
                ->map(fn(string $status) => (int) ($paymentStatusCounts[$status] ?? 0))
                ->values()
                ->all(),
        ];

        $topTours = Tour::query()
            ->join('booking', 'booking.tour_id', '=', 'tours.tour_id')
            ->where('booking.payment_status', 'paid')
            ->whereBetween('booking.booking_date', [$filterFrom, $filterTo])
            ->groupBy('tours.tour_id', 'tours.name')
            ->orderByDesc(DB::raw('SUM(booking.total_price)'))
            ->limit(5)
            ->get([
                'tours.tour_id',
                'tours.name',
                DB::raw('COUNT(booking.booking_id) as booking_count'),
                DB::raw('COALESCE(SUM(booking.total_price), 0) as revenue'),
            ]);

        $recentBookings = Booking::query()
            ->with(['tour', 'customer'])
            ->whereBetween('booking_date', [$filterFrom, $filterTo])
            ->orderByDesc('booking_date')
            ->limit(6)
            ->get();

        $monthlyChartYear = (int) $today->year;
        $monthlyRaw = Booking::query()
            ->whereYear('booking_date', $monthlyChartYear)
            ->selectRaw('MONTH(booking_date) as month_no')
            ->selectRaw('COUNT(*) as bookings_count')
            ->selectRaw("COALESCE(SUM(CASE WHEN payment_status = 'paid' THEN total_price ELSE 0 END), 0) as revenue_total")
            ->groupBy('month_no')
            ->orderBy('month_no')
            ->get()
            ->keyBy('month_no');

        $monthlyChartLabels   = [];
        $monthlyBookingSeries = [];
        $monthlyRevenueSeries = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthlyChartLabels[]   = 'Th' . $month;
            $monthlyBookingSeries[] = (int) data_get($monthlyRaw->get($month), 'bookings_count', 0);
            $monthlyRevenueSeries[] = round((float) data_get($monthlyRaw->get($month), 'revenue_total', 0), 2);
        }

        // ── Revenue chart: 4 groupings (Ngày / Tuần / Tháng / Năm) ───────────
        // Daily — last 30 days
        $rawDay = Booking::query()
            ->where('payment_status', 'paid')
            ->whereBetween('booking_date', [$today->copy()->subDays(29)->startOfDay(), $today->copy()->endOfDay()])
            ->selectRaw('DATE(booking_date) as k')
            ->selectRaw('COALESCE(SUM(total_price), 0) as v')
            ->groupBy('k')->orderBy('k')
            ->pluck('v', 'k');

        $revenueDay = ['labels' => [], 'data' => []];
        for ($i = 29; $i >= 0; $i--) {
            $d = $today->copy()->subDays($i);
            $revenueDay['labels'][] = $d->format('d/m');
            $revenueDay['data'][]   = (int) round((float) ($rawDay[$d->format('Y-m-d')] ?? 0));
        }

        // Weekly — last 12 weeks (Mon-based)
        $weekStart12 = $today->copy()->subWeeks(11)->startOfWeek(\Carbon\Carbon::MONDAY);
        $rawWeek = Booking::query()
            ->where('payment_status', 'paid')
            ->whereBetween('booking_date', [$weekStart12->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->selectRaw('YEARWEEK(booking_date, 1) as k')
            ->selectRaw('COALESCE(SUM(total_price), 0) as v')
            ->groupBy('k')->orderBy('k')
            ->pluck('v', 'k');

        $revenueWeek = ['labels' => [], 'data' => []];
        for ($i = 11; $i >= 0; $i--) {
            $ws  = $today->copy()->subWeeks($i)->startOfWeek(\Carbon\Carbon::MONDAY);
            $key = ((int) $ws->format('o')) * 100 + (int) $ws->format('W');
            $revenueWeek['labels'][] = 'T' . $ws->format('W') . '/' . $ws->format('y');
            $revenueWeek['data'][]   = (int) round((float) ($rawWeek[$key] ?? 0));
        }

        // Monthly — current year (reuse $monthlyRaw)
        $revenueMonth = ['labels' => $monthlyChartLabels, 'data' => $monthlyRevenueSeries];

        // Yearly — last 5 years
        $rawYear = Booking::query()
            ->where('payment_status', 'paid')
            ->where('booking_date', '>=', $today->copy()->subYears(4)->startOfYear())
            ->selectRaw('YEAR(booking_date) as k')
            ->selectRaw('COALESCE(SUM(total_price), 0) as v')
            ->groupBy('k')->orderBy('k')
            ->pluck('v', 'k');

        $revenueYear = ['labels' => [], 'data' => []];
        for ($y = $today->year - 4; $y <= $today->year; $y++) {
            $revenueYear['labels'][] = (string) $y;
            $revenueYear['data'][]   = (int) round((float) ($rawYear[$y] ?? 0));
        }

        return view('admin.dashboard', compact(
            'stats',
            'chartLabels',
            'bookingTrendSeries',
            'revenueTrendSeries',
            'monthlyChartLabels',
            'monthlyBookingSeries',
            'monthlyRevenueSeries',
            'monthlyChartYear',
            'revenueDay',
            'revenueWeek',
            'revenueMonth',
            'revenueYear',
            'bookingStatusChart',
            'paymentStatusChart',
            'topTours',
            'recentBookings',
            'preset',
            'filterFrom',
            'filterTo',
            'filterLabel'
        ));
    }

    private function calculateTrend(float $current, float $previous): ?float
    {
        if ($current === 0.0 && $previous === 0.0) {
            return null;
        }

        if ($previous === 0.0) {
            return 100.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
