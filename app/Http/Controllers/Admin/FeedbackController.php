<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // Xem danh sách feedback
    public function index()
    {
        $feedbacks = Feedback::all(); // Lấy tất cả feedback
        return view('admin.feedback.index', compact('feedbacks')); // Trả về view danh sách feedback
    }

    // Ẩn feedback (cập nhật trạng thái)
    public function hide($id)
    {
        $feedback = Feedback::findOrFail($id); // Tìm feedback theo ID
        $feedback->update(['type' => 'an']); // Cập nhật trạng thái thành "an"
        return redirect()->back()->with('success', 'Feedback đã được ẩn.');
    }

    // Tự động xóa feedback có nội dung spam
    public function deleteSpam(Request $request)
    {
        $spamContent = $request->input('spam_content'); // Nội dung spam cần xóa
        Feedback::where('content', 'LIKE', "%{$spamContent}%")->delete(); // Xóa các feedback chứa nội dung spam
        return redirect()->back()->with('success', 'Feedback spam đã được xóa.');
    }
}
