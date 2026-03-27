<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Show bookings awaiting admin confirmation.
     */
    public function index()
    {
        $bookings = Booking::with(['tour', 'customer', 'schedule'])
            ->where('admin_confirmed', false)
            ->orderBy('booking_date', 'desc')
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Confirm a booking (admin action).
     */
    public function confirm(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->admin_confirmed = true;
        $booking->save();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking confirmed.');
    }
}
