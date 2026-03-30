<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuideFeedback;
use Illuminate\Http\Request;

class GuideFeedbackController extends Controller
{
    /**
     * Show list of all feedbacks from guides
     */
    public function index(Request $request)
    {
        $query = GuideFeedback::with('guide.user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Search by title or content
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        $feedbacks = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->query());

        $stats = [
            'total' => GuideFeedback::count(),
            'pending' => GuideFeedback::where('status', 'pending')->count(),
            'viewed' => GuideFeedback::where('status', 'viewed')->count(),
            'resolved' => GuideFeedback::where('status', 'resolved')->count(),
        ];

        return view('admin.guide-feedback.index', compact('feedbacks', 'stats'));
    }

    /**
     * Show feedback detail
     */
    public function show($id)
    {
        $feedback = GuideFeedback::with('guide.user')->findOrFail($id);

        // Update status to viewed
        if ($feedback->status === 'pending') {
            $feedback->update(['status' => 'viewed']);
        }

        return view('admin.guide-feedback.show', compact('feedback'));
    }

    /**
     * Update feedback status
     */
    public function updateStatus(Request $request, $id)
    {
        $feedback = GuideFeedback::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,viewed,resolved',
            'admin_reply' => 'nullable|string|max:5000',
        ]);

        $feedback->update([
            'status' => $validated['status'],
            'admin_reply' => $validated['admin_reply'] ?? $feedback->admin_reply,
        ]);

        return redirect()->back()->with('success', 'Trạng thái phản hồi đã được cập nhật!');
    }

    /**
     * Delete feedback
     */
    public function destroy($id)
    {
        $feedback = GuideFeedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('admin.guide-feedback.index')
            ->with('success', 'Phản hồi đã được xóa!');
    }
}
