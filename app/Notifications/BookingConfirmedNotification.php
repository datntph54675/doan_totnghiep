<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $booking = $this->booking;
        $tour = $booking->tour;
        $schedule = $booking->schedule;
        $customer = $booking->customer;

        return (new MailMessage)
            ->subject('[GoTour] Xác nhận đặt tour thành công - ' . $tour->name)
            ->greeting('Chào ' . $customer->fullname . ',')
            ->line('Chúc mừng! Đơn đặt tour của bạn đã được admin xác nhận thành công.')
            ->line('**Thông tin tour:**')
            ->line('🏷️ **Tên tour:** ' . $tour->name)
            ->line('📅 **Ngày khởi hành:** ' . $schedule->start_date->format('d/m/Y'))
            ->line('📅 **Ngày kết thúc:** ' . $schedule->end_date->format('d/m/Y'))
            ->line('👥 **Số lượng khách:** ' . $booking->participant_count . ' người')
            ->line('💰 **Tổng tiền:** ' . number_format($booking->total_price, 0, ',', '.') . ' VNĐ')
            ->line('📍 **Điểm tập trung:** ' . ($schedule->meeting_point ?? 'Sẽ thông báo sau'))
            ->line('📞 **Liên hệ hỗ trợ:** 1900-xxxx hoặc email: support@gotour.vn')
            ->action('Xem chi tiết booking', url('/booking/' . $booking->booking_id . '/success'))
            ->line('Cảm ơn bạn đã tin tưởng và lựa chọn GoTour!')
            ->salutation('Trân trọng, Đội ngũ GoTour');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
