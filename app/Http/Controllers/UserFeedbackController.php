<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Feedback;
use App\Services\TourAvailabilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFeedbackController extends Controller
{
    public function store(Request $request, int $bookingId): RedirectResponse
    {
        app(TourAvailabilityService::class)->sync();

        $booking = Booking::with(['feedbacks', 'schedule', 'tour'])->findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền đánh giá booking này.');
        }

        if (! $booking->canBeReviewed()) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá tour đã hoàn thành và chưa đánh giá trước đó.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
            'booking_id' => 'required|integer',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.integer' => 'Số sao đánh giá không hợp lệ.',
            'rating.min' => 'Số sao đánh giá tối thiểu là 1.',
            'rating.max' => 'Số sao đánh giá tối đa là 5.',
            'content.required' => 'Vui lòng nhập nội dung đánh giá.',
            'content.string' => 'Nội dung đánh giá không hợp lệ.',
            'content.max' => 'Nội dung đánh giá không được vượt quá :max ký tự.',
            'booking_id.required' => 'Không xác định được booking cần đánh giá.',
            'booking_id.integer' => 'Mã booking không hợp lệ.',
        ]);

        if ((int) $validated['booking_id'] !== $booking->booking_id) {
            return back()->with('error', 'Thông tin đánh giá không hợp lệ.');
        }

        Feedback::create([
            'booking_id' => $booking->booking_id,
            'type' => Feedback::TYPE_REVIEW,
            'rating' => $validated['rating'],
            'content' => $validated['content'],
            'is_hidden' => false,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Gửi đánh giá thành công. Cảm ơn bạn đã phản hồi về tour!');
    }
}
