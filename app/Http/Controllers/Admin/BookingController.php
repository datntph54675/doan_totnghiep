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
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $query = Booking::with(['tour', 'customer', 'schedule'])
            ->orderBy('booking_date', 'desc');

        if ($status === 'confirmed') {
            $query->where('admin_confirmed', true);
        } elseif ($status === 'all') {
            // Keep all bookings
        } else {
            $status = 'pending';
            $query->where('admin_confirmed', false);
        }

        $bookings = $query->paginate(20)->appends(['status' => $status]);

        $pendingCount = Booking::where('admin_confirmed', false)->count();
        $confirmedCount = Booking::where('admin_confirmed', true)->count();

        return view('admin.bookings.index', compact('bookings', 'status', 'pendingCount', 'confirmedCount'));
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

    /**
     * Show booking details in admin.
     */
    public function show(Request $request, $id)
    {
        $booking = Booking::with(['tour', 'customer', 'schedule'])
            ->where('booking_id', $id)
            ->firstOrFail();

        return view('admin.bookings.show', compact('booking'));
    }
}
