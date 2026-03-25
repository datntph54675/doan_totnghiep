<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\DepartureSchedule;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create($tourId)
    {
        $tour = Tour::with(['category', 'departureSchedules'])
            ->where('status', 'active')
            ->findOrFail($tourId);

        $schedules = DepartureSchedule::where('tour_id', $tourId)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get();

        return view('user.booking', compact('tour', 'schedules'));
    }

    public function store(Request $request, $tourId)
    {
        $tour = Tour::findOrFail($tourId);

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
            'num_people'     => $validated['num_people'],
            'total_price'    => $totalPrice,
            'booking_date'   => now(),
            'status'         => 'upcoming',
            'payment_status' => 'unpaid',
            'note'           => $validated['note'] ?? null,
        ]);

        return redirect()->route('user.booking.success', $booking->booking_id);
    }

    public function success($bookingId)
    {
        $booking = Booking::with(['tour', 'schedule', 'customer'])->findOrFail($bookingId);
        return view('user.booking-success', compact('booking'));
    }
}
