<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['tour', 'schedule', 'customer'])
            ->orderBy('booking_id', 'desc')
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['tour', 'schedule', 'customer', 'feedbacks'])
            ->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
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
        ],[
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

        return redirect()->route('admin.bookings.index', $booking)->with('success', 'Booking đã được cập nhật.');
    }
}
