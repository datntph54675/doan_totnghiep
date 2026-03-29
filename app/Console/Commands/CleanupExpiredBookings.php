<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingExpiredNotification;
use Illuminate\Support\Facades\DB;

class CleanupExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-expired-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hủy các đơn đặt tour quá 3 phút chưa thanh toán và giải phóng vé';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredBookings = Booking::where('status', 'upcoming')
            ->where('payment_status', 'unpaid')
            ->where('expires_at', '<', now())
            ->with(['schedule', 'customer', 'tour'])
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('Không có đơn hàng nào hết hạn.');
            return;
        }

        /** @var \App\Models\Booking $booking */
        foreach ($expiredBookings as $booking) {
            DB::transaction(function () use ($booking) {
                // 1. Hủy đơn và lưu ghi chú
                $booking->update([
                    'status' => 'cancelled',
                    'note' => ($booking->note ? $booking->note . PHP_EOL : '') . 'Hệ thống tự động hủy do quá hạn thanh toán 3 phút.'
                ]);

                // 2. Hoàn trả vé vào kho
                if ($booking->schedule) {
                    $booking->schedule()->increment('available_spots', $booking->num_people);
                }

                $this->info("Đã hủy đơn #{$booking->booking_id} và hoàn trả {$booking->num_people} chỗ.");

                // 3. KHÔNG XÓA MỀM để đơn vẫn hiện ở mục "Đã hủy"
                // $booking->delete();

                // 4. Gửi thông báo cho khách (nếu có user_id)
                if ($booking->user_id) {
                    $user = User::find($booking->user_id);
                    if ($user) {
                        $user->notify(new BookingExpiredNotification($booking));

                        // 5. KIỂM TRA BLACKLIST (Quy tắc 1: 3 đơn hết hạn trong 24h)
                        // Sử dụng withTrashed() để đếm cả đơn đã bị xóa mềm
                        $expiredCount = Booking::withTrashed()
                            ->where('user_id', $user->user_id)
                            ->where('status', 'cancelled')
                            ->where('note', 'like', '%Hệ thống tự động hủy do quá hạn thanh toán%')
                            ->where('updated_at', '>=', now()->subDay())
                            ->count();

                        if ($expiredCount >= 3) {
                            $user->update(['is_blacklisted' => true]);
                            $this->warn("User #{$user->user_id} đã bị đưa vào BLACKLIST do quá 3 đơn hết hạn trong 24h.");
                        }
                    }
                }
            });
        }

        $this->info('Hoàn tất dọn dẹp đơn hàng hết hạn.');
    }
}
