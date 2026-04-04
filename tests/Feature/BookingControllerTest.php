<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Tour;
use App\Models\DepartureSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the confirm method works correctly.
     */
    public function test_confirm_booking(): void
    {
        // Create test data
        $customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'fullname' => 'Test Customer'
        ]);

        $tour = Tour::factory()->create();
        $schedule = DepartureSchedule::factory()->create();

        $booking = Booking::factory()->create([
            'customer_id' => $customer->customer_id,
            'tour_id' => $tour->tour_id,
            'schedule_id' => $schedule->schedule_id,
            'payment_status' => 'paid',
            'admin_confirmed' => false,
            'status' => 'upcoming'
        ]);

        // Act as admin and confirm booking
        $response = $this->post("/admin/bookings/{$booking->booking_id}/confirm");

        // Assert redirect back with success message
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Đã xác nhận booking cho khách thành công.');

        // Assert booking is confirmed
        $booking->refresh();
        $this->assertTrue($booking->admin_confirmed);
    }
}
