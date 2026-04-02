<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\DepartureSchedule;
use App\Models\Attendance;
use App\Models\Itinerary;
use App\Models\Feedback;
use App\Models\GuideAssignment;
use App\Services\TourAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class GuideController extends Controller
{
    private function keepPaidGroups(Collection $participants): Collection
    {
        $remainingCompanions = 0;

        return $participants->filter(function ($participant) use (&$remainingCompanions) {
            if ($participant->is_representative) {
                $isPaid = ($participant->payment_status ?? null) === 'paid';

                if ($isPaid) {
                    $remainingCompanions = max(0, $participant->group_size - 1);

                    return true;
                }

                $remainingCompanions = 0;

                return false;
            }

            if ($remainingCompanions > 0) {
                $remainingCompanions--;

                return true;
            }

            return false;
        })->values();
    }

    private function assignParticipantGroups(Collection $participants): Collection
    {
        $groupNumber = 0;
        $activeGroupKey = null;
        $remainingCompanions = 0;

        return $participants->map(function ($participant) use (&$groupNumber, &$activeGroupKey, &$remainingCompanions) {
            if ($participant->is_representative) {
                $groupNumber++;
                $activeGroupKey = 'booking-' . $participant->booking_id;
                $remainingCompanions = max(0, $participant->group_size - 1);
            } elseif ($remainingCompanions > 0 && $activeGroupKey) {
                $remainingCompanions--;
            } else {
                $groupNumber++;
                $activeGroupKey = 'guest-' . $participant->tour_customer_id;
                $remainingCompanions = 0;
            }

            $participant->group_key = $activeGroupKey;
            $participant->group_name = 'Nhóm ' . $groupNumber;

            return $participant;
        });
    }

    private function scheduleParticipants(int $scheduleId): Collection
    {
        $participants = DB::table('tour_customer as tc')
            ->join('customer as c', 'c.customer_id', '=', 'tc.customer_id')
            ->leftJoin('booking as b', function ($join) {
                $join->on('b.schedule_id', '=', 'tc.schedule_id')
                    ->on('b.customer_id', '=', 'tc.customer_id');
            })
            ->where('tc.schedule_id', $scheduleId)
            ->select([
                'tc.id as tour_customer_id',
                'tc.schedule_id',
                'tc.customer_id',
                'c.fullname',
                'c.phone',
                'c.email',
                'c.id_number',
                'b.booking_id',
                'b.num_people',
                'b.status as booking_status',
                'b.payment_status',
            ])
            ->orderBy('tc.id')
            ->get()
            ->map(function ($participant) {
                $participant->is_representative = !empty($participant->booking_id);
                $participant->group_size = max(1, (int) ($participant->num_people ?? 1));

                return $participant;
            });

        $participants = $this->keepPaidGroups($participants);

        return $this->assignParticipantGroups($participants);
    }

    private function bookingPassengerTotal(Collection $bookings): int
    {
        return (int) $bookings->sum('participant_count');
    }

    private function assignedScheduleIds(int $guideId)
    {
        return GuideAssignment::where('guide_id', $guideId)
            ->whereIn('status', ['accepted', 'completed'])
            ->pluck('schedule_id');
    }

    public function dashboard()
    {
        app(TourAvailabilityService::class)->sync();

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

        $assignedScheduleIds = $this->assignedScheduleIds($guide->guide_id);

        $upcomingTours = DepartureSchedule::whereIn('schedule_id', $assignedScheduleIds)
            ->where('status', 'scheduled')
            ->with(['tour', 'bookings'])
            ->orderBy('start_date', 'asc')
            ->get();

        $ongoingTours = DepartureSchedule::whereIn('schedule_id', $assignedScheduleIds)
            ->where('status', 'ongoing')
            ->with(['tour', 'bookings'])
            ->orderBy('start_date', 'asc')
            ->get();

        $completedTours = DepartureSchedule::whereIn('schedule_id', $assignedScheduleIds)
            ->where('status', 'completed')
            ->with(['tour', 'bookings'])
            ->orderBy('end_date', 'desc')
            ->limit(5)
            ->get();

        $currentPassengerTotal = $upcomingTours->sum(fn($schedule) => $this->bookingPassengerTotal($schedule->bookings))
            + $ongoingTours->sum(fn($schedule) => $this->bookingPassengerTotal($schedule->bookings));

        return view('guide.dashboard', compact('guide', 'upcomingTours', 'ongoingTours', 'completedTours', 'currentPassengerTotal'));
    }

    public function tourDetail($scheduleId)
    {
        app(TourAvailabilityService::class)->sync();

        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();
        $assignedScheduleIds = $this->assignedScheduleIds($guide->guide_id);

        $schedule = DepartureSchedule::with(['tour', 'bookings.customer'])
            ->where('schedule_id', $scheduleId)
            ->whereIn('schedule_id', $assignedScheduleIds)
            ->firstOrFail();

        // Lấy itinerary của tour
        $itineraries = Itinerary::where('tour_id', $schedule->tour_id)
            ->orderBy('day_number', 'asc')
            ->get();

        // Lấy feedback từ các booking của schedule này
        $feedbacks = Feedback::whereIn('booking_id', $schedule->bookings->pluck('booking_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        $participants = $this->scheduleParticipants((int) $schedule->schedule_id);
        $totalPassengers = max($this->bookingPassengerTotal($schedule->bookings), $participants->count());

        return view('guide.tour-detail', compact('schedule', 'itineraries', 'feedbacks', 'totalPassengers', 'participants'));
    }

    public function attendance($scheduleId)
    {
        app(TourAvailabilityService::class)->sync();

        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();
        $assignedScheduleIds = $this->assignedScheduleIds($guide->guide_id);

        $schedule = DepartureSchedule::with(['tour', 'bookings.customer'])
            ->where('schedule_id', $scheduleId)
            ->whereIn('schedule_id', $assignedScheduleIds)
            ->firstOrFail();

        $participants = $this->scheduleParticipants((int) $scheduleId);

        // Lấy lịch sử điểm danh
        $attendances = Attendance::where('schedule_id', $scheduleId)
            ->with('customer')
            ->orderBy('marked_at', 'desc')
            ->get();

        $latestAttendanceByParticipant = $attendances
            ->filter(fn($attendance) => !empty($attendance->tour_customer_id))
            ->unique('tour_customer_id')
            ->keyBy('tour_customer_id');

        $totalPassengers = max($this->bookingPassengerTotal($schedule->bookings), $participants->count());

        return view('guide.attendance', compact('schedule', 'attendances', 'participants', 'latestAttendanceByParticipant', 'totalPassengers'));
    }

    public function markAttendance(Request $request, $scheduleId)
    {
        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        $assignedScheduleIds = $this->assignedScheduleIds($guide->guide_id);
        DepartureSchedule::where('schedule_id', $scheduleId)
            ->whereIn('schedule_id', $assignedScheduleIds)
            ->firstOrFail();

        $validated = $request->validate([
            'tour_customer_id' => 'required|exists:tour_customer,id',
            'status' => 'required|in:present,absent,unknown',
            'note' => 'nullable|string',
        ]);

        $tourCustomer = DB::table('tour_customer')
            ->where('id', $validated['tour_customer_id'])
            ->where('schedule_id', $scheduleId)
            ->first();

        if (!$tourCustomer) {
            return redirect()->route('guide.attendance', $scheduleId)
                ->with('error', 'Khách hàng không thuộc chuyến đi này.');
        }

        Attendance::create([
            'schedule_id' => $scheduleId,
            'tour_customer_id' => $tourCustomer->id,
            'customer_id' => $tourCustomer->customer_id,
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
        app(TourAvailabilityService::class)->sync();

        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();
        $assignedScheduleIds = $this->assignedScheduleIds($guide->guide_id);

        $schedule = DepartureSchedule::with('tour')
            ->where('schedule_id', $scheduleId)
            ->whereIn('schedule_id', $assignedScheduleIds)
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
        app(TourAvailabilityService::class)->sync();

        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        $assignedScheduleIds = $guide
            ? $this->assignedScheduleIds($guide->guide_id)
            : collect();

        $totalTours = $guide
            ? DepartureSchedule::whereIn('schedule_id', $assignedScheduleIds)->where('status', 'completed')->count()
            : 0;

        $totalCustomers = $guide
            ? (int) \App\Models\Booking::whereIn('schedule_id', $assignedScheduleIds)->sum('num_people')
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
        app(TourAvailabilityService::class)->sync();

        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        if (!$guide) {
            return view('guide.customer-list', [
                'participants' => collect(),
                'totalPassengers' => 0,
            ]);
        }

        $assignedScheduleIds = $this->assignedScheduleIds($guide->guide_id);

        $participants = DB::table('tour_customer as tc')
            ->join('customer as c', 'c.customer_id', '=', 'tc.customer_id')
            ->join('departure_schedule as ds', 'ds.schedule_id', '=', 'tc.schedule_id')
            ->join('tours as t', 't.tour_id', '=', 'ds.tour_id')
            ->leftJoin('booking as b', function ($join) {
                $join->on('b.customer_id', '=', 'tc.customer_id')
                    ->on('b.schedule_id', '=', 'tc.schedule_id');
            })
            ->whereIn('tc.schedule_id', $assignedScheduleIds)
            ->select([
                'tc.id as tour_customer_id',
                'tc.schedule_id',
                'c.customer_id',
                'c.fullname',
                'c.email',
                'c.phone',
                'c.id_number',
                't.name as tour_name',
                'ds.start_date',
                'b.booking_id',
                'b.num_people',
                'b.status as booking_status',
                'b.payment_status',
            ])
            ->orderByDesc('ds.start_date')
            ->orderBy('tc.id')
            ->get()

            ->map(function ($participant) {
                $participant->is_representative = !empty($participant->booking_id);
                $participant->group_size = max(1, (int) ($participant->num_people ?? 1));

                return $participant;
            })
            ->values();

        $participants = $participants->groupBy('schedule_id')->flatMap(function ($scheduleParticipants) {
            return $this->keepPaidGroups($scheduleParticipants->values());
        })->values();

        $currentScheduleId = null;

        $participants = $participants->groupBy('schedule_id')->flatMap(function ($scheduleParticipants) {
            return $this->assignParticipantGroups($scheduleParticipants->values());
        })->values()->map(function ($participant) use (&$currentScheduleId) {
            if ($currentScheduleId !== $participant->schedule_id) {
                $currentScheduleId = $participant->schedule_id;
            }

            return $participant;
        });

        $totalPassengers = $participants->count();

        return view('guide.customer-list', compact('participants', 'totalPassengers'));
    }

    /**
     * Hiển thị danh sách tour chưa được xác nhận (pending)
     */
    public function assignmentList()
    {
        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        if (!$guide) {
            return view('guide.assignment-list', ['assignments' => collect()]);
        }

        // Lấy danh sách assignment chờ xác nhận và từ chối
        $assignments = GuideAssignment::where('guide_id', $guide->guide_id)
            ->whereIn('status', ['pending', 'rejected'])
            ->with(['schedule.tour', 'assigner'])
            ->orderBy('assigned_at', 'desc')
            ->get();

        return view('guide.assignment-list', compact('assignments', 'guide'));
    }

    /**
     * Guide xác nhận nhận tour
     */
    public function acceptAssignment(Request $request, $assignmentId)
    {
        $assignment = GuideAssignment::findOrFail($assignmentId);
        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        if (!$guide) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin hướng dẫn viên');
        }

        // Kiểm tra xem assignment có thuộc về guide đang đăng nhập không
        if ($assignment->guide_id !== $guide->guide_id) {
            return redirect()->back()->with('error', 'Không có quyền truy cập');
        }

        // Kiểm tra xem assignment ở trạng thái pending hoặc rejected
        if (!in_array($assignment->status, ['pending', 'rejected'])) {
            return redirect()->back()->with('error', 'Không thể xác nhận tour này');
        }

        $assignment->update([
            'status' => 'accepted',
            'confirmed_at' => now(),
            'rejection_reason' => null, // Xóa lý do từ chối nếu có
        ]);

        return redirect()->route('guide.assignments')->with('success', 'Bạn đã xác nhận nhận tour thành công');
    }

    /**
     * Guide từ chối nhận tour
     */
    public function rejectAssignment(Request $request, $assignmentId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:5|max:500',
        ], [
            'rejection_reason.required' => 'Vui lòng nhập lý do từ chối',
            'rejection_reason.min' => 'Lý do từ chối phải ít nhất 5 ký tự',
            'rejection_reason.max' => 'Lý do từ chối không được vượt quá 500 ký tự',
        ]);

        $assignment = GuideAssignment::findOrFail($assignmentId);
        $user = Auth::user();
        $guide = Guide::where('user_id', $user->user_id)->first();

        if (!$guide) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin hướng dẫn viên');
        }

        // Kiểm tra xem assignment có thuộc về guide đang đăng nhập không
        if ($assignment->guide_id !== $guide->guide_id) {
            return redirect()->back()->with('error', 'Không có quyền truy cập');
        }

        // Kiểm tra xem assignment ở trạng thái pending hoặc accepted
        if (!in_array($assignment->status, ['pending', 'accepted'])) {
            return redirect()->back()->with('error', 'Không thể từ chối tour này');
        }

        $assignment->update([
            'status' => 'rejected',
            'confirmed_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('guide.assignments')->with('success', 'Bạn đã từ chối tour. Admin sẽ thấy lý do của bạn');
    }
}
