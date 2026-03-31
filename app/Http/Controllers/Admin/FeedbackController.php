<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::query()
            ->reviews()
            ->with(['booking.tour', 'booking.customer']);

        if ($request->filled('visibility')) {
            if ($request->visibility === 'hidden') {
                $query->hidden();
            } elseif ($request->visibility === 'visible') {
                $query->visible();
            }
        }

        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->rating);
        }

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->keyword);

            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('content', 'like', "%{$keyword}%")
                    ->orWhereHas('booking.tour', function ($tourQuery) use ($keyword) {
                        $tourQuery->where('name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('booking.customer', function ($customerQuery) use ($keyword) {
                        $customerQuery->where('fullname', 'like', "%{$keyword}%");
                    });
            });
        }

        $feedbacks = $query
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function hide($id)
    {
        $feedback = Feedback::reviews()->findOrFail($id);

        if ($feedback->isHidden()) {
            return redirect()->back()->with('success', 'Đánh giá này đã được ẩn trước đó.');
        }

        $feedback->hide();

        return redirect()->back()->with('success', 'Đã ẩn đánh giá khỏi trang công khai.');
    }

    public function unhide($id)
    {
        $feedback = Feedback::reviews()->findOrFail($id);

        if (! $feedback->isHidden()) {
            return redirect()->back()->with('success', 'Đánh giá này đang hiển thị công khai.');
        }

        $feedback->unhide();

        return redirect()->back()->with('success', 'Đã hiển thị lại đánh giá.');
    }
}
