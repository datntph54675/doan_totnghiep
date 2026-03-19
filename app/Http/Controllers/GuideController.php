<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\DepartureSchedule;
use App\Models\Attendance;
use App\Models\Itinerary;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuideController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        if (!$guide) {
            return view('guide.dashboard')->with([
                'guide' => null,
                'upcomingTours' => collect(),
                'ongoingTours' => collect(),
                'completedTours' => collect(),
            ]);
        }

        $upcomingTours = DepartureSchedule::where('guide_id', $guide->guide_id)
            ->where('start_date', '>=', now())
            ->with(['tour', 'bookings'])
            ->orderBy('start_date', 'asc')
            ->get();

        $ongoingTours = DepartureSchedule::where('guide_id', $guide->guide_id)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->with(['tour', 'bookings'])
            ->get();

        $completedTours = DepartureSchedule::where('guide_id', $guide->guide_id)
            ->where('end_date', '<', now())
            ->with(['tour', 'bookings'])
            ->orderBy('end_date', 'desc')
            ->limit(5)
            ->get();

        return view('guide.dashboard', compact('guide', 'upcomingTours', 'ongoingTours', 'completedTours'));
    }

    public function tourDetail($scheduleId)
    {
        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        $schedule = DepartureSchedule::with(['tour', 'bookings.customer'])
            ->where('schedule_id', $scheduleId)
            ->where('guide_id', $guide->guide_id)
            ->firstOrFail();

        // Lấy itinerary của tour
        $itineraries = Itinerary::where('tour_id', $schedule->tour_id)
            ->orderBy('day_number', 'asc')
            ->get();

        // Lấy feedback từ các booking của schedule này
        $feedbacks = Feedback::whereIn('booking_id', $schedule->bookings->pluck('booking_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guide.tour-detail', compact('schedule', 'itineraries', 'feedbacks'));
    }

    public function attendance($scheduleId)
    {
        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        $schedule = DepartureSchedule::with(['tour', 'bookings.customer'])
            ->where('schedule_id', $scheduleId)
            ->where('guide_id', $guide->guide_id)
            ->firstOrFail();

        // Lấy danh sách điểm danh
        $attendances = Attendance::where('schedule_id', $scheduleId)
            ->with('customer')
            ->orderBy('marked_at', 'desc')
            ->get();

        return view('guide.attendance', compact('schedule', 'attendances'));
    }

    public function markAttendance(Request $request, $scheduleId)
    {
        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        $validated = $request->validate([
            'customer_id' => 'required|exists:customer,customer_id',
            'status' => 'required|in:present,absent,unknown',
            'note' => 'nullable|string',
        ]);

        Attendance::create([
            'schedule_id' => $scheduleId,
            'customer_id' => $validated['customer_id'],
            'guide_id' => $guide->guide_id,
            'status' => $validated['status'],
            'note' => $validated['note'] ?? null,
            'marked_at' => now(),
        ]);

        return redirect()->route('guide.attendance', $scheduleId)
            ->with('success', 'Điểm danh thành công!');
    }

    public function itinerary($scheduleId)
    {
        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        $schedule = DepartureSchedule::with('tour')
            ->where('schedule_id', $scheduleId)
            ->where('guide_id', $guide->guide_id)
            ->firstOrFail();

        $itineraries = Itinerary::where('tour_id', $schedule->tour_id)
            ->orderBy('day_number', 'asc')
            ->get();

        return view('guide.itinerary', compact('schedule', 'itineraries'));
    }

    public function updateItinerary(Request $request, $itineraryId)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $itinerary = Itinerary::findOrFail($itineraryId);

        // Chỉ cập nhật các field được gửi lên
        if (isset($validated['title'])) {
            $itinerary->title = $validated['title'];
        }
        if (isset($validated['description'])) {
            $itinerary->description = $validated['description'];
        }
        if (isset($validated['location'])) {
            $itinerary->location = $validated['location'];
        }

        $itinerary->save();

        return back()->with('success', 'Cập nhật lịch trình thành công!');
    }

    public function profile()
    {
        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        $totalTours = $guide
            ? DepartureSchedule::where('guide_id', $guide->guide_id)->where('end_date', '<', now())->count()
            : 0;

        $totalCustomers = $guide
            ? \App\Models\Booking::whereIn(
                'schedule_id',
                DepartureSchedule::where('guide_id', $guide->guide_id)->pluck('schedule_id')
            )->distinct('customer_id')->count('customer_id')
            : 0;

        return view('guide.profile', compact('guide', 'totalTours', 'totalCustomers'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullname' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'language' => 'nullable|string|max:255',
            'certificate' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'experience' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Cập nhật user
        $userData = [
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
        ];
        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }
        \App\Models\User::where('user_id', $user->user_id)->update($userData);

        // Cập nhật guide profile
        $guide = Guide::where('user_id', $user->user_id)->first();
        if ($guide) {
            $guide->update([
                'language' => $request->language,
                'certificate' => $request->certificate,
                'specialization' => $request->specialization,
                'experience' => $request->experience,
            ]);
        }

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
    public function customerList()
    {
        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        if (!$guide) {
            return view('guide.customer-list', ['customers' => collect()]);
        }

        // Lấy danh sách khách hàng từ các tour của hướng dẫn viên
        $customers = \App\Models\Booking::with('customer')
            ->whereIn('schedule_id', DepartureSchedule::where('guide_id', $guide->guide_id)->pluck('schedule_id'))
            ->get()
            ->map(function ($booking) {
                return $booking->customer;
            });

        return view('guide.customer-list', compact('customers'));
    }
}