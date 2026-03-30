<?php

namespace App\Http\Controllers\Admin;

use App\Models\GuideAssignment;
use App\Models\DepartureSchedule;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class GuideAssignmentController extends Controller
{
    /**
     * Display a listing of guide assignments.
     */
    public function index()
    {
        $assignments = GuideAssignment::with(['schedule.tour', 'guide.user', 'assigner'])
            ->orderBy('assigned_at', 'desc')
            ->paginate(10);

        return view('admin.guide_assignment.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new guide assignment.
     */
    public function create()
    {
        $schedules = DepartureSchedule::with('tour')
            ->where('status', 'scheduled')
            ->get();
        $guides = Guide::with('user')->get();

        return view('admin.guide_assignment.create', compact('schedules', 'guides'));
    }

    /**
     * Store a newly created guide assignment in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'schedule_id' => 'required|exists:departure_schedule,schedule_id',
            'guide_id' => 'required|exists:guide,guide_id',
            'note' => 'nullable|string',
        ]);

        $data['assigned_by'] = Auth::id() ?? (Auth::user()->user_id ?? null);
        $data['assigned_at'] = now();
        $data['status'] = 'pending'; // Khởi tạo trạng thái là chờ xác nhận

        $exists = GuideAssignment::where('schedule_id', $data['schedule_id'])
            ->where('guide_id', $data['guide_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['guide_id' => 'HDV này đã được phân công cho lịch trình đã chọn.']);
        }

        GuideAssignment::create($data);

        return redirect()->route('admin.guide-assignments.index')
            ->with('success', 'Phân công HDV thành công');
    }

    /**
     * Display the specified guide assignment.
     */
    public function show(GuideAssignment $guideAssignment)
    {
        $guideAssignment->load(['schedule.tour', 'guide.user', 'assigner']);
        return view('admin.guide_assignment.show', compact('guideAssignment'));
    }

    /**
     * Show the form for editing the specified guide assignment.
     */
    public function edit(GuideAssignment $guideAssignment)
    {
        $guideAssignment->load(['schedule.tour', 'guide.user', 'assigner']);
        $schedules = DepartureSchedule::with('tour')->get();
        $guides = Guide::with('user')->get();

        return view('admin.guide_assignment.edit', compact('guideAssignment', 'schedules', 'guides'));
    }

    /**
     * Update the specified guide assignment in storage.
     */
    public function update(Request $request, GuideAssignment $guideAssignment)
    {
        $data = $request->validate([
            'schedule_id' => 'required|exists:departure_schedule,schedule_id',
            'guide_id' => 'required|exists:guide,guide_id',
            'note' => 'nullable|string',
        ]);

        $exists = GuideAssignment::where('schedule_id', $data['schedule_id'])
            ->where('guide_id', $data['guide_id'])
            ->where('id', '!=', $guideAssignment->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['guide_id' => 'HDV này đã được phân công cho lịch trình đã chọn.']);
        }

        $isReassigned = (int) $guideAssignment->guide_id !== (int) $data['guide_id']
            || (int) $guideAssignment->schedule_id !== (int) $data['schedule_id'];

        if ($isReassigned) {
            $data['status'] = 'pending';
            $data['confirmed_at'] = null;
            $data['rejection_reason'] = null;
            $data['assigned_at'] = now();
            $data['assigned_by'] = Auth::id() ?? (Auth::user()->user_id ?? null);
        }

        $guideAssignment->update($data);

        return redirect()->route('admin.guide-assignments.index')
            ->with(
                'success',
                $isReassigned
                    ? 'Đã phân công lại HDV. Trạng thái xác nhận đã được đặt lại về chờ xác nhận.'
                    : 'Cập nhật phân công HDV thành công'
            );
    }

    /**
     * Remove the specified guide assignment from storage.
     */
    public function destroy(GuideAssignment $guideAssignment)
    {
        $guideAssignment->delete();

        return redirect()->route('admin.guide-assignments.index')
            ->with('success', 'Xóa phân công HDV thành công');
    }
}
