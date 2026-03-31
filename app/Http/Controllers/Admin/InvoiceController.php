<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->query('search');
        $payMethod  = $request->query('payment_method');
        $payStatus  = $request->query('payment_status');
        $dateFrom   = $request->query('date_from');
        $dateTo     = $request->query('date_to');

        $query = Booking::with(['tour', 'schedule', 'customer'])
            ->whereIn('payment_status', ['paid', 'refunded', 'deposit']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_id', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($c) => $c->where('fullname', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%"))
                  ->orWhereHas('tour', fn($t) => $t->where('name', 'like', "%{$search}%"));
            });
        }

        if ($payMethod) {
            $query->where('payment_method', $payMethod);
        }

        if ($payStatus) {
            $query->where('payment_status', $payStatus);
        }

        if ($dateFrom) {
            $query->whereDate('booking_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('booking_date', '<=', $dateTo);
        }

        $invoices = $query->orderByDesc('booking_id')->paginate(20)->withQueryString();

        $totalRevenue = (clone $query)->where('payment_status', 'paid')->sum('total_price');
        $totalCount   = $invoices->total();

        return view('admin.invoices.index', compact(
            'invoices', 'totalRevenue', 'totalCount',
            'search', 'payMethod', 'payStatus', 'dateFrom', 'dateTo'
        ));
    }

    public function show($id)
    {
        $booking = Booking::with(['tour', 'schedule', 'customer'])->findOrFail($id);

        // Chỉ hiển thị hóa đơn cho booking đã có thanh toán
        if (!in_array($booking->payment_status, ['paid', 'refunded', 'deposit'])) {
            return redirect()->route('admin.invoices.index')
                ->with('error', 'Booking này chưa có hóa đơn thanh toán.');
        }

        return view('admin.invoices.show', compact('booking'));
    }
}
