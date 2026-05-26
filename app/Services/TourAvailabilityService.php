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

        $this->syncSchedules($today);
        $this->syncBookings();
        $this->syncTours($today);
    }

    private function syncSchedules(Carbon $today): void
    {
        DepartureSchedule::query()
            ->where('status', '!=', DepartureSchedule::STATUS_CANCELLED)
            ->chunkById(100, function ($schedules) use ($today) {
                foreach ($schedules as $schedule) {
                    $startDate = $schedule->start_date;
                    $endDate = $schedule->end_date;

                    if (! $startDate || ! $endDate) {
                        continue;
                    }

                    $nextStatus = match (true) {
                        $endDate->isPast() => DepartureSchedule::STATUS_COMPLETED,
                        $startDate->isAfter($today) => DepartureSchedule::STATUS_SCHEDULED,
                        default => DepartureSchedule::STATUS_ONGOING,
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
                    $schedule = $booking->schedule;

                    if (! $schedule) {
                        continue;
                    }

                    $scheduleStart = $schedule->start_date;
                    $scheduleEnd = $schedule->end_date;

                    $nextStatus = 'upcoming';

                    if ($scheduleEnd && $scheduleEnd->isPast()) {
                        $nextStatus = 'completed';
                    } elseif ($scheduleStart && ! $scheduleStart->isAfter(Carbon::today()) && $scheduleEnd && ! $scheduleEnd->isBefore(Carbon::today())) {
                        $nextStatus = 'ongoing';
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
                    $hasActiveSchedule = $tour->departureSchedules()
                        ->where('status', '!=', DepartureSchedule::STATUS_CANCELLED)
                        ->whereDate('end_date', '>=', $today)
                        ->exists();

                    $nextStatus = $hasActiveSchedule ? 'active' : 'inactive';

                    if ($tour->status !== $nextStatus) {
                        $tour->update(['status' => $nextStatus]);
                    }
                }
            }, 'tour_id');
    }
}
