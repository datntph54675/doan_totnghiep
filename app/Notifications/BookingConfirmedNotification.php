<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingConfirmedNotification extends Notification
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
        $tour = $this->booking->tour;
        $schedule = $this->booking->schedule;
        $customer = $this->booking->customer;

        return (new MailMessage)
            ->subject('[GoTour] Booking của bạn đã được xác nhận - ' . $tour->name)
            ->greeting('Xin chào ' . $customer->fullname . ',')
            ->line('🎉 Chúc mừng! Booking của bạn đã được Admin xác nhận thành công.')
            ->line('')
            ->line('📍 **THÔNG TIN TOUR:**')
            ->line('Tên tour: **' . $tour->name . '**')
            ->line('Ngày khởi hành: **' . $schedule->start_date->format('d/m/Y H:i') . '**')
            ->line('Ngày kết thúc: **' . $schedule->end_date->format('d/m/Y H:i') . '**')
            ->line('Số lượng khách: **' . $this->booking->num_people . ' người**')
            ->line('Tổng giá: **' . number_format($this->booking->total_price, 0, '.', ',') . ' VNĐ**')
            ->line('')
            ->line('📋 **THÔNG TIN LUẬT SỬ DỤNG:**')
            ->line($tour->policy ?? 'Không có thông tin chính sách.')
            ->line('')
            ->line('🚐 **NHÀ CUNG CẤP:**')
            ->line($tour->supplier ?? 'Não có thông tin nhà cung cấp.')
            ->line('')
            ->line('💬 **GHI CHÚ:** ')
            ->line($this->booking->note ?? 'Không có ghi chú.')
            ->line('')
            ->line('Vui lòng chuẩn bị sẵn sàng trước ngày khởi hành. Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với bộ phận hỗ trợ của GoTour.')
            ->action('Xem chi tiết booking', route('user.profile'))
            ->line('')
            ->line('Trân trọng cảm ơn bạn đã sử dụng dịch vụ GoTour!');
    }

    public function toArray($notifiable): array
    {
        return [
            'booking_id' => $this->booking->booking_id,
            'tour_id' => $this->booking->tour_id,
        ];
    }
}
