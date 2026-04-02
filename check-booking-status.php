<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use App\Models\Feedback;

$bookings = Booking::with('schedule', 'tour')->get();
echo "=== BOOKING STATUS ===\n";
echo "Total: " . $bookings->count() . "\n\n";

foreach ($bookings as $b) {
    echo "ID: {$b->booking_id} | Tour: " . ($b->tour ? $b->tour->name : 'N/A') . "\n";
    echo "  Status: {$b->status} | Payment: {$b->payment_status}\n";
    $endDate = $b->schedule ? $b->schedule->end_date : 'N/A';
    echo "  Schedule End: " . $endDate . "\n";
    echo "  Has Review: " . ($b->hasReview() ? 'YES' : 'NO') . "\n";
    echo "  Can Review: " . ($b->canBeReviewed() ? 'YES' : 'NO') . "\n";

    if (!$b->canBeReviewed()) {
        $reasons = [];
        if ($b->status !== 'completed') $reasons[] = "status not completed ({$b->status})";
        if ($b->payment_status !== 'paid') $reasons[] = "payment not paid ({$b->payment_status})";
        if ($b->hasReview()) $reasons[] = "already has review";
        if ($b->schedule && $b->schedule->end_date && $b->schedule->end_date->isFuture()) {
            $reasons[] = "schedule in future ({$b->schedule->end_date})";
        }
        echo "  Reason: " . implode(", ", $reasons) . "\n";
    }
    echo "\n";
}

// Fix: Make all completed bookings reviewable
echo "=== FIXING BOOKINGS ===\n";
foreach ([3, 4] as $bid) {
    $b = Booking::find($bid);
    if ($b) {
        $b->status = 'completed';
        $b->payment_status = 'paid';
        $b->save();

        if ($b->schedule) {
            $b->schedule->update(['end_date' => now()->subDays(1)->toDateString()]);
        }

        Feedback::where('booking_id', $bid)->where('type', 'review')->delete();
        echo "Booking #{$bid}: canBeReviewed = " . ($b->fresh()->canBeReviewed() ? 'YES' : 'NO') . "\n";
    }
}
