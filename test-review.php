<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Booking;
use App\Models\Feedback;

$bookings = Booking::with('schedule')->get();
foreach ($bookings as $b) {
    if ($b->status === 'completed' && $b->payment_status === 'paid') {
        if ($b->schedule && $b->schedule->end_date->isFuture()) {
            $b->schedule->update(['end_date' => now()->subDays(1)->toDateString()]);
        }
        Feedback::where('booking_id', $b->booking_id)->where('type', 'review')->delete();
    }
}
echo "Fixed all bookings for review.\n";
