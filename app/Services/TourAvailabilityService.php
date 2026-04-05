<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Support\Carbon;

class TourAvailabilityService
{
    public function sync(): void
    {
        $today = Carbon::today();

        $this->syncBookings();
        $this->syncTours($today);
    }

    private function syncBookings(): void
    {
        Booking::query()
            ->with('schedule')
            ->where('status', '!=', 'cancelled')
            ->chunkById(100, function ($bookings) {
                foreach ($bookings as $booking) {
                    $schedule = $booking->schedule;
                    $scheduleStatus = $schedule?->status;
                    $scheduleEnd = $schedule?->end_date;

                    if (! $schedule) {
                        continue;
                    }

                    $nextStatus = match ($scheduleStatus) {
                        'completed' => 'completed',
                        'ongoing' => 'ongoing',
                        default => 'upcoming',
                    };

                    if ($scheduleEnd && $scheduleEnd->isPast()) {
                        $nextStatus = 'completed';
                    }

                    if ($booking->status !== $nextStatus) {
                        $booking->update(['status' => $nextStatus]);
                    }
                }
            }, 'booking_id');
    }

    private function syncTours(Carbon $today): void
    {
        Tour::query()
            ->chunkById(100, function ($tours) use ($today) {
                foreach ($tours as $tour) {
                    $hasOpenRegistration = $tour->departureSchedules()
                        ->where('status', 'scheduled')
                        ->whereDate('start_date', '>', $today)
                        ->exists();

                    $nextStatus = $hasOpenRegistration ? 'active' : 'inactive';

                    if ($tour->status !== $nextStatus) {
                        $tour->update(['status' => $nextStatus]);
                    }
                }
            }, 'tour_id');
    }
}
