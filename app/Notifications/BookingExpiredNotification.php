<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingExpiredNotification extends Notification
{
    // Removing ShouldQueue for direct testing

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[GoTour] Thông báo: Đơn hàng đặt tour của bạn đã hết hạn')
            ->greeting('Xin chào ' . $this->booking->customer->fullname . ',')
            ->line('Chúng tôi rất tiếc phải thông báo rằng đơn hàng đặt tour "' . $this->booking->tour->name . '" của bạn đã hết hạn thanh toán.')
            ->line('Do quá 3 phút mà hệ thống chưa nhận được thông tin thanh toán, vị trí của bạn đã được giải phóng để nhường chỗ cho khách hàng khác.')
            ->action('Đặt lại tour ngay', route('tours.show', $this->booking->tour_id))
            ->line('Nếu bạn gặp khó khăn trong quá trình thanh toán, vui lòng liên hệ với bộ phận hỗ trợ của GoTour.')
            ->line('Trân trọng cảm ơn!');
    }

    public function toArray($notifiable): array
    {
        return [
            'booking_id' => $this->booking->booking_id,
        ];
    }
}
