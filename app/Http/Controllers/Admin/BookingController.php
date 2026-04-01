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
                ->where('status', '!=', 'cancelled')
                ->where('admin_confirmed', false);
        } elseif ($activeTab === 'pending-refund') {
            $query->where('status', 'cancelled')
                ->where('payment_status', 'paid');
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
            ->where('status', '!=', 'cancelled')
            ->where('admin_confirmed', false)
            ->count();

        $pendingRefundCount = Booking::where('status', 'cancelled')
            ->where('payment_status', 'paid')
            ->count();

        $tours = Tour::orderBy('name')->get(['tour_id', 'name']);

        return view('admin.bookings.index', compact(
            'bookings',
            'activeTab',
            'allCount',
            'pendingConfirmationCount',
            'pendingRefundCount',
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

        if ($booking->isCancelled()) {
            return redirect()->back()->with('error', 'Booking đã bị hủy nên không thể xác nhận.');
        }

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

    public function refund($id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking->canBeRefundedByAdmin()) {
            return redirect()->back()->with('error', 'Booking này không thuộc diện cần hoàn tiền.');
        }

        $note = trim((string) $booking->note);
        $refundNote = 'Admin đã xác nhận hoàn tiền vào ' . now()->format('d/m/Y H:i');

        $booking->update([
            'payment_status' => 'refunded',
            'note' => $note !== '' ? $note . PHP_EOL . $refundNote : $refundNote,
        ]);

        return redirect()->back()->with('success', 'Đã đánh dấu hoàn tiền cho booking này.');
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $currentStatus = $booking->status;
        $currentPayment = $booking->payment_status;

        // Xây dựng rules động tùy theo tình trạng thanh toán
        $statusRule = $currentPayment === 'unpaid' 
            ? 'prohibited|in:' . $currentStatus  // Chỉ cho phép status hiện tại khi chưa thanh toán
            : 'required|in:upcoming,ongoing,completed,cancelled'; // Require khi đã thanh toán

        $data = $request->validate([
            'status' => $statusRule,
            'payment_status' => 'required|in:unpaid,deposit,paid,refunded',
            'note' => 'nullable|string',
        ], [
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'status.prohibited' => 'Không thể thay đổi trạng thái khi chưa thanh toán.',
            'payment_status.required' => 'Trạng thái thanh toán là bắt buộc.',
            'payment_status.in' => 'Trạng thái thanh toán không hợp lệ.',
        ]);

        // Khi chưa thanh toán, status sẽ không có trong $data, cần set default
        if (!isset($data['status'])) {
            $data['status'] = $currentStatus;
        }

        // Admin chưa xác nhận thì không được thay đổi trạng thái booking
        if (!$booking->canEditStatus() && $data['status'] !== $currentStatus) {
            return back()->withErrors(['status' => 'Admin chưa xác nhận booking nên không thể thay đổi trạng thái.']);
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

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking đã được cập nhật.');
    }
}
