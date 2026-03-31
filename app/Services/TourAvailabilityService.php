<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\DepartureSchedule;
use App\Models\Tour;
use Illuminate\Support\Carbon;

class TourAvailabilityService
{
    public function sync(): void
    {
        $today = Carbon::today();

        $this->syncDepartureSchedules($today);
        $this->syncBookings();
        $this->syncTours($today);
    }

    private function syncDepartureSchedules(Carbon $today): void
    {
        DepartureSchedule::query()
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->chunkById(100, function ($schedules) use ($today) {
                foreach ($schedules as $schedule) {
                    $nextStatus = match (true) {
                        $schedule->end_date?->lt($today) => 'completed',
                        $schedule->start_date?->lte($today) && $schedule->end_date?->gte($today) => 'ongoing',
                        default => 'scheduled',
                    };

                    if ($schedule->status !== $nextStatus) {
                        $schedule->update(['status' => $nextStatus]);
                    }
                }
            }, 'schedule_id');
    }

    private function syncBookings(): void
    {
        Booking::query()
            ->with('schedule')
            ->where('status', '!=', 'cancelled')
            ->chunkById(100, function ($bookings) {
                foreach ($bookings as $booking) {
                    $scheduleStatus = $booking->schedule?->status;

                    if (!$scheduleStatus) {
                        continue;
                    }

                    $nextStatus = match ($scheduleStatus) {
                        'completed' => 'completed',
                        'ongoing' => 'ongoing',
                        default => 'upcoming',
                    };

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
