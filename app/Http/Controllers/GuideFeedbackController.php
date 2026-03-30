<?php

namespace App\Http\Controllers;

use App\Models\GuideFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuideFeedbackController extends Controller
{
    /**
     * Show form to create feedback
     */
    public function create()
    {
        return view('guide.feedback.create');
    }

    /**
     * Store feedback
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:danh_gia,su_co',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10|max:5000',
        ], [
            'type.required' => 'Vui lòng chọn loại phản hồi',
            'type.in' => 'Loại phản hồi không hợp lệ',
            'title.required' => 'Vui lòng nhập tiêu đề',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'content.required' => 'Vui lòng nhập nội dung phản hồi',
            'content.min' => 'Nội dung phản hồi phải có ít nhất 10 ký tự',
            'content.max' => 'Nội dung phản hồi không được vượt quá 5000 ký tự',
        ]);

        $user = Auth::user();
        $guide = $user->guide;

        if (!$guide) {
            return redirect()->back()
                ->with('error', 'Bạn không phải là hướng dẫn viên. Vui lòng kiểm tra lại tài khoản.');
        }

        GuideFeedback::create([
            'guide_id' => $guide->guide_id,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return redirect()->route('guide.feedback.list')
            ->with('success', 'Phản hồi của bạn đã được gửi thành công! Admin sẽ xem xét và phản hồi trong thời gian sớm nhất.');
    }

    /**
     * Show list of feedbacks from current guide
     */
    public function list(Request $request)
    {
        $user = Auth::user();
        $guide = $user->guide;

        if (!$guide) {
            return redirect()->route('guide.dashboard')
                ->with('error', 'Bạn không phải là hướng dẫn viên.');
        }

        $query = GuideFeedback::where('guide_id', $guide->guide_id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $feedbacks = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        $stats = [
            'total' => GuideFeedback::where('guide_id', $guide->guide_id)->count(),
            'pending' => GuideFeedback::where('guide_id', $guide->guide_id)->where('status', 'pending')->count(),
            'viewed' => GuideFeedback::where('guide_id', $guide->guide_id)->where('status', 'viewed')->count(),
            'resolved' => GuideFeedback::where('guide_id', $guide->guide_id)->where('status', 'resolved')->count(),
        ];

        return view('guide.feedback.list', compact('feedbacks', 'stats'));
    }

    /**
     * Show feedback detail
     */
    public function show($id)
    {
        $user = Auth::user();
        $guide = $user->guide;

        $feedback = GuideFeedback::where('guide_id', $guide->guide_id)
            ->findOrFail($id);

        return view('guide.feedback.show', compact('feedback'));
    }
}
