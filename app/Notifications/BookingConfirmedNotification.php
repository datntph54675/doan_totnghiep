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

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $booking  = $this->booking;
        $tour     = $booking->tour;
        $schedule = $booking->schedule;
        $customer = $booking->customer;

        return (new MailMessage)
            ->subject('[GoTour] Xác nhận đặt tour thành công - ' . $tour->name)
            ->view('emails.booking-confirmed', compact('booking', 'tour', 'schedule', 'customer'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->booking_id,
            'tour_id'    => $this->booking->tour_id,
        ];
    }
}
