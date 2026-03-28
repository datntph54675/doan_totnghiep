<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\DepartureSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create($tourId)
    {
        $tour = Tour::visibleToUsers()
            ->with(['category', 'departureSchedules'])
            ->findOrFail($tourId);

        $schedules = DepartureSchedule::where('tour_id', $tourId)
            ->whereDate('start_date', '>=', Carbon::today())
            ->where('status', 'scheduled')
            ->orderBy('start_date', 'asc')
            ->get();

        $user = Auth::user();

        return view('user.booking', compact('tour', 'schedules', 'user'));
    }

    public function store(Request $request, $tourId)
    {
        $tour = Tour::visibleToUsers()->findOrFail($tourId);

        $validated = $request->validate([
            'schedule_id'  => 'required|exists:departure_schedule,schedule_id',
            'fullname'     => 'required|string|max:100',
            'gender'       => 'required|in:Nam,Nữ,Khác',
            'birthdate'    => 'nullable|date',
            'phone'        => 'required|string|max:15',
            'email'        => 'nullable|email|max:100',
            'id_number'    => 'nullable|string|max:20',
            'num_people'   => 'required|integer|min:1|max:50',
            'note'         => 'nullable|string',
        ]);

        $schedule = DepartureSchedule::where('schedule_id', $validated['schedule_id'])
            ->where('tour_id', $tour->tour_id)
            ->where('status', 'scheduled')
            ->whereDate('start_date', '>=', Carbon::today())
            ->first();

        if (!$schedule) {
            return back()->withErrors([
                'schedule_id' => 'Lịch khởi hành không hợp lệ hoặc không còn khả dụng.'
            ])->withInput();
        }

        // Kiểm tra chỗ trống
        if ($validated['num_people'] > $schedule->available_spots) {
            return back()->withErrors([
                'num_people' => "Rất tiếc, chỉ còn {$schedule->available_spots} chỗ trống cho ngày khởi hành này."
            ])->withInput();
        }

        // Tạo hoặc tìm customer theo phone
        $customer = Customer::firstOrCreate(
            ['phone' => $validated['phone']],
            [
                'fullname'   => $validated['fullname'],
                'gender'     => $validated['gender'],
                'birthdate'  => $validated['birthdate'] ?? null,
                'email'      => $validated['email'] ?? null,
                'id_number'  => $validated['id_number'] ?? null,
            ]
        );

        $totalPrice = $tour->price * $validated['num_people'];

        $booking = Booking::create([
            'customer_id'    => $customer->customer_id,
            'tour_id'        => $tour->tour_id,
            'schedule_id'    => $validated['schedule_id'],
            'user_id'        => Auth::id(), // Lưu user_id nếu đã đăng nhập
            'num_people'     => $validated['num_people'],
            'total_price'    => $totalPrice,
            'booking_date'   => now(),
            'status'         => 'upcoming',
            'payment_status' => 'unpaid',
            'note'           => $validated['note'] ?? null,
        ]);

        return redirect()->route('payment.choose', $booking->booking_id);
    }

    public function success($bookingId)
    {
        $booking = Booking::with(['tour', 'schedule', 'customer'])->findOrFail($bookingId);
        return view('user.booking-success', compact('booking'));
    }

    public function cancel($bookingId)
    {
        $booking = Booking::with(['schedule'])->findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->isCancelled()) {
            return redirect()->to(route('user.profile') . '#bookings')
                ->with('success', 'Booking này đã được hủy trước đó.');
        }

        if (!$booking->canBeCancelledByUser()) {
            return redirect()->to(route('user.profile') . '#bookings')
                ->with('error', 'Booking này không còn đủ điều kiện để hủy.');
        }

        $note = trim((string) $booking->note);
        $cancelNote = 'Khách hàng đã hủy booking vào ' . now()->format('d/m/Y H:i');

        $booking->update([
            'status' => 'cancelled',
            'admin_confirmed' => false,
            'note' => $note !== '' ? $note . PHP_EOL . $cancelNote : $cancelNote,
        ]);

        $message = $booking->payment_status === 'paid'
            ? 'Đã hủy booking. Bộ phận hỗ trợ sẽ liên hệ với bạn về phần thanh toán đã thực hiện.'
            : 'Đã hủy booking thành công.';

        return redirect()->to(route('user.profile') . '#bookings')->with('success', $message);
    }
}
