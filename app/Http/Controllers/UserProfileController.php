<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('user.profile', compact('user'));
    }

    public function bookings()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Tự động kiểm tra và hủy các đơn hàng hết hạn ngay khi xem trang (Lazy Cleanup)
        $this->expireOldBookings($user->user_id);

        return view('user.booking-status', [
            'user' => $user,
            ...$this->getBookingStatusData($user->user_id),
        ]);
    }

    private function expireOldBookings(int $userId)
    {
        $expiredBookings = Booking::where('user_id', $userId)
            ->where('status', 'upcoming')
            ->where('payment_status', 'unpaid')
            ->where('expires_at', '<', now())
            ->get();

        /** @var Booking $booking */
        foreach ($expiredBookings as $booking) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($booking) {
                $booking->update([
                    'status' => 'cancelled',
                    'note' => ($booking->note ? $booking->note . PHP_EOL : '') . 'Hệ thống tự động hủy do quá hạn thanh toán.'
                ]);

                if ($booking->schedule) {
                    $booking->schedule()->increment('available_spots', $booking->num_people);
                }
                
                // Gửi thông báo
                $user = \App\Models\User::find($booking->user_id);
                if ($user) {
                    $user->notify(new \App\Notifications\BookingExpiredNotification($booking));
                }
            });
        }
    }

    private function getBookingStatusData(int $userId): array
    {
        $unpaidBookings = Booking::with(['tour', 'schedule'])
            ->where('user_id', $userId)
            ->where('payment_status', 'unpaid')
            ->where('status', '!=', 'cancelled')
            ->orderByDesc('booking_date')
            ->get();

        $pendingBookings = Booking::with(['tour', 'schedule'])
            ->where('user_id', $userId)
            ->where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->where('admin_confirmed', false)
            ->orderByDesc('booking_date')
            ->get();

        $confirmedBookings = Booking::with(['tour', 'schedule'])
            ->where('user_id', $userId)
            ->where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->where('admin_confirmed', true)
            ->orderByDesc('booking_date')
            ->get();

        $cancelledBookings = Booking::with(['tour', 'schedule'])
            ->where('user_id', $userId)
            ->where('status', 'cancelled')
            ->orderByDesc('booking_date')
            ->get();

        return compact('unpaidBookings', 'pendingBookings', 'confirmedBookings', 'cancelledBookings');
    }

    /**
     * Update basic user profile information.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')],
            'phone' => ['required', 'string', 'max:15'],
        ]);

        $user->update([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Cập nhật thông tin cá nhân thành công!');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.current_password' => 'Mật khẩu hiện tại không chính xác.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
