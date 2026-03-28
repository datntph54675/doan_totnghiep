<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'all');
        $tourId = $request->query('tour_id');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = Booking::with(['tour', 'schedule', 'customer']);

        if ($activeTab === 'pending-confirmation') {
            $query->where('payment_status', 'paid')
                ->where('admin_confirmed', false);
        }

        if (!empty($tourId)) {
            $query->where('tour_id', $tourId);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('booking_date', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('booking_date', '<=', $dateTo);
        }

        $bookings = $query
            ->orderBy('booking_id', 'desc')
            ->paginate(20)
            ->withQueryString();

        $allCount = Booking::count();
        $pendingConfirmationCount = Booking::where('payment_status', 'paid')
            ->where('admin_confirmed', false)
            ->count();

        $tours = Tour::orderBy('name')->get(['tour_id', 'name']);

        return view('admin.bookings.index', compact(
            'bookings',
            'activeTab',
            'allCount',
            'pendingConfirmationCount',
            'tours',
            'tourId',
            'dateFrom',
            'dateTo'
        ));
    }

    public function show($id)
    {
        $booking = Booking::with(['tour', 'schedule', 'customer', 'feedbacks'])
            ->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Booking chưa thanh toán đủ nên chưa thể xác nhận.');
        }

        if ($booking->admin_confirmed) {
            return redirect()->back()->with('success', 'Booking này đã được admin xác nhận trước đó.');
        }

        $booking->update([
            'admin_confirmed' => true,
        ]);

        return redirect()->back()->with('success', 'Đã xác nhận booking cho khách thành công.');
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $currentStatus = $booking->status;
        $currentPayment = $booking->payment_status;

        $data = $request->validate([
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'payment_status' => 'required|in:unpaid,deposit,paid',
            'note' => 'nullable|string',
        ], [
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'payment_status.required' => 'Trạng thái thanh toán là bắt buộc.',
            'payment_status.in' => 'Trạng thái thanh toán không hợp lệ.',
        ]);

        // Chưa thanh toán thì chưa đươc phép thay đổi trạng thái
        if ($currentPayment === 'unpaid' && $data['status'] !== $currentStatus) {
            return back()->withErrors(['status' => 'Không thể thay đổi trạng thái khi chưa thanh toán.']);
        }

        // Trạng thái không được đổi tùy tiện phải theo quy trình
        if ($data['status'] !== $currentStatus) {
            $allowedTransitions = [
                'upcoming' => ['ongoing', 'cancelled'],
                'ongoing' => ['completed'],
                'completed' => [],
                'cancelled' => [],
            ];

            if (!in_array($data['status'], $allowedTransitions[$currentStatus] ?? [])) {
                return back()->withErrors(['status' => 'Không thể chuyển trạng thái này.']);
            }
        }

        $booking->update($data);

        if ($currentPayment !== 'paid' && $data['payment_status'] === 'paid') {
            $booking->update(['admin_confirmed' => false]);
        }

        return redirect()->route('admin.bookings.index', $booking)->with('success', 'Booking đã được cập nhật.');
    }
}
