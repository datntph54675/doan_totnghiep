<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\DepartureSchedule;
use App\Services\TourAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create($tourId)
    {
        app(TourAvailabilityService::class)->sync();

        $tour = Tour::visibleToUsers()
            ->with(['category', 'departureSchedules'])
            ->findOrFail($tourId);

        $schedules = DepartureSchedule::where('tour_id', $tourId)
            ->whereDate('start_date', '>', Carbon::today())
            ->where('status', 'scheduled')
            ->orderBy('start_date', 'asc')
            ->get();

        $user = Auth::user();

        return view('user.booking', compact('tour', 'schedules', 'user'));
    }

    public function store(Request $request, $tourId)
    {
        app(TourAvailabilityService::class)->sync();

        $user = Auth::user();

        // 1. Kiểm tra Blacklist
        if ($user->is_blacklisted) {
            return back()->with('error', 'Tài khoản của bạn đã bị khóa tính năng đặt chỗ do vi phạm chính sách thanh toán nhiều lần. Vui lòng liên hệ Admin để được hỗ trợ.')->withInput();
        }

        // 2. Kiểm tra giới hạn 1 đơn chờ thanh toán
        $hasPending = Booking::where('user_id', $user->user_id)
            ->where('payment_status', 'unpaid')
            ->where('status', 'upcoming')
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'Bạn đang có một đơn hàng chờ thanh toán. Vui lòng hoàn tất thanh toán hoặc hủy đơn cũ trước khi đặt tour mới.')->withInput();
        }

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

            'companions' => 'nullable|array',
            'companions.*.fullname' => 'nullable|string|max:100',
            'companions.*.gender' => 'nullable|in:Nam,Nữ,Khác',
            'companions.*.birthdate' => 'nullable|date',
            'companions.*.phone' => 'nullable|string|max:15',
            'note'         => 'nullable|string',
        ]);

        $numPeople = (int) $validated['num_people'];
        $companions = array_values($validated['companions'] ?? []);

        if ($numPeople >= 2) {
            $requiredCompanions = $numPeople - 1;

            if (count($companions) !== $requiredCompanions) {
                throw ValidationException::withMessages([
                    'companions' => "Vui lòng nhập đầy đủ thông tin cho {$requiredCompanions} người đi cùng.",
                ]);
            }

            foreach ($companions as $index => $companion) {
                $isIncomplete = empty(trim((string) ($companion['fullname'] ?? '')))
                    || empty(trim((string) ($companion['gender'] ?? '')))
                    || empty(trim((string) ($companion['birthdate'] ?? '')))
                    || empty(trim((string) ($companion['phone'] ?? '')));

                if ($isIncomplete) {
                    $position = $index + 1;

                    throw ValidationException::withMessages([
                        'companions' => "Người đi cùng #{$position}: vui lòng nhập đầy đủ họ tên, giới tính, ngày sinh và số điện thoại.",
                    ]);
                }
            }
        }

        try {
            return DB::transaction(function () use ($validated, $tour, $user, $companions) {
                // 3. Sử dụng lockForUpdate để tránh Race Condition (Tranh chấp vé)
                $schedule = DepartureSchedule::where('schedule_id', $validated['schedule_id'])
                    ->where('tour_id', $tour->tour_id)
                    ->where('status', 'scheduled')
                    ->whereDate('start_date', '>', Carbon::today())
                    ->lockForUpdate()
                    ->first();

                if (!$schedule) {
                    throw new \Exception('Lịch khởi hành không hợp lệ hoặc không còn khả dụng.');
                }

                // 4. Kiểm tra chỗ trống thực tế trong DB sau khi đã Lock
                if ($validated['num_people'] > $schedule->available_spots) {
                    throw new \Exception("Rất tiếc, chỉ còn {$schedule->available_spots} chỗ trống cho ngày khởi hành này.");
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

                // 5. Tạo Booking với thời hạn 3 phút
                $booking = Booking::create([
                    'customer_id'    => $customer->customer_id,
                    'tour_id'        => $tour->tour_id,
                    'schedule_id'    => $validated['schedule_id'],
                    'user_id'        => $user->user_id,
                    'num_people'     => $validated['num_people'],
                    'total_price'    => $totalPrice,
                    'booking_date'   => now(),
                    'status'         => 'upcoming',
                    'payment_status' => 'unpaid',
                    'note'           => $validated['note'] ?? null,
                    'expires_at'     => now()->addMinutes(3),
                ]);

                $tourCustomers = [
                    [
                        'schedule_id' => $validated['schedule_id'],
                        'customer_id' => $customer->customer_id,
                    ],
                ];

                foreach ($companions as $companion) {
                    $companionCustomer = Customer::create([
                        'fullname' => $companion['fullname'],
                        'gender' => $companion['gender'],
                        'birthdate' => $companion['birthdate'] ?? null,
                        'phone' => $companion['phone'],
                        'email' => null,
                        'id_number' => null,
                    ]);

                    $tourCustomers[] = [
                        'schedule_id' => $validated['schedule_id'],
                        'customer_id' => $companionCustomer->customer_id,
                    ];
                }

                DB::table('tour_customer')->insert($tourCustomers);

                // 6. Trừ vé ngay lập tức để giữ chỗ (Giai đoạn 1)
                $schedule->decrement('available_spots', $validated['num_people']);

                return redirect()->route('payment.choose', $booking->booking_id);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function success($bookingId)
    {
        $booking = Booking::with(['tour', 'schedule', 'customer'])->findOrFail($bookingId);

        // Security check: only the owner can see the success page
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem thông tin này.');
        }

        return view('user.booking-success', compact('booking'));
    }

    public function cancel($bookingId)
    {
        $booking = Booking::with(['schedule'])->findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Logic hủy mới: Chỉ cho phép tự hủy nếu CHƯA thanh toán
        if ($booking->payment_status !== 'unpaid') {
            return redirect()->to(route('user.profile') . '#bookings')
                ->with('error', 'Booking đã thanh toán không thể tự hủy. Vui lòng liên hệ hỗ trợ.');
        }

        if ($booking->isCancelled()) {
            return redirect()->to(route('user.profile') . '#bookings')
                ->with('success', 'Booking này đã được hủy trước đó.');
        }

        if (!$booking->canBeCancelledByUser()) {
            return redirect()->to(route('user.profile') . '#bookings')
                ->with('error', 'Booking này không còn đủ điều kiện để hủy.');
        }

        return DB::transaction(function () use ($booking) {
            $user = Auth::user();
            $note = trim((string) $booking->note);
            $cancelNote = 'Khách hàng đã hủy booking vào ' . now()->format('d/m/Y H:i');

            // Cập nhật ghi chú trước khi xóa mềm
            $booking->update([
                'status' => 'cancelled',
                'admin_confirmed' => false,
                'note' => $note !== '' ? $note . PHP_EOL . $cancelNote : $cancelNote,
            ]);

            // Trả lại vé vào kho khi khách chủ động hủy
            $booking->schedule()->increment('available_spots', $booking->num_people);

            // 7. KIỂM TRA BLACKLIST (Quy tắc 2: 3 lần Hủy thủ công trong 1 giờ)
            // Sử dụng withTrashed() để đếm cả các đơn đã bị xóa mềm
            $manualCancelCount = Booking::withTrashed()
                ->where('user_id', $user->user_id)
                ->where('status', 'cancelled')
                ->where('note', 'like', 'Khách hàng đã hủy booking vào%')
                ->where('updated_at', '>=', now()->subHour())
                ->count();

            if ($manualCancelCount >= 3) {
                \App\Models\User::where('user_id', $user->user_id)->update(['is_blacklisted' => true]);
            }

            return redirect()->back()
                ->with('success', 'Đã hủy và xóa đơn đặt tour thành công.');
        });
    }
}
