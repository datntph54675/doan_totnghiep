<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Tour;
use App\Models\DepartureSchedule;
use App\Models\User;
use App\Models\Booking;

$cat = Category::where('status', 'active')->first();
if (!$cat) {
    $cat = Category::create(['name' => 'Test Cat', 'status' => 'active']);
}

tour:
$tour = Tour::create([
    'name' => 'Tour Tự Động',
    'description' => 'Tour test',
    'policy' => 'Chính sách test',
    'supplier' => 'GoTour',
    'image' => 'https://via.placeholder.com/400',
    'price' => 1000000,
    'duration' => 3,
    'status' => 'active',
    'category_id' => $cat->category_id,
]);

$schedule = DepartureSchedule::create([
    'tour_id' => $tour->tour_id,
    'start_date' => now()->addDays(2)->toDateString(),
    'end_date' => now()->addDays(4)->toDateString(),
    'status' => 'scheduled',
    'available_spots' => 20,
]);

$user = User::first();
if (!$user) {
    throw new RuntimeException('Không tìm thấy user nào trong hệ thống.');
}

$booking = Booking::create([
    'schedule_id' => $schedule->schedule_id,
    'customer_id' => 1,
    'tour_id' => $tour->tour_id,
    'user_id' => $user->user_id,
    'booking_date' => now(),
    'num_people' => 2,
    'total_price' => 2000000,
    'status' => 'completed',
    'admin_confirmed' => 1,
    'payment_status' => 'paid',
    'payment_method' => 'vnpay',
    'expires_at' => now()->addDays(5),
]);

echo "Tour: {$tour->tour_id}, Schedule: {$schedule->schedule_id}, Booking: {$booking->booking_id}\n";
