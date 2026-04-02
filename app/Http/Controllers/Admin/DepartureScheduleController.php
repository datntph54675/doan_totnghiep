<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepartureSchedule;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartureScheduleController extends Controller
{
    public function create($tourId)
    {
        $tour = Tour::findOrFail($tourId);
        $defaultStatus = DepartureSchedule::STATUS_SCHEDULED;

        return view('admin.tours.departure_schedules.create', compact('tour', 'defaultStatus'));
    }

    public function store(Request $request, $tourId)
    {
        $tour = Tour::findOrFail($tourId);

        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_people' => 'required|integer|min:1',
            'meeting_point' => 'nullable|string|max:255',
            'status' => ['required', Rule::in([DepartureSchedule::STATUS_SCHEDULED])],
            'notes' => 'nullable|string',
        ], [
            'status.in' => 'Lịch xuất phát mới phải bắt đầu ở trạng thái Đã lên lịch.',
        ]);

        $data['tour_id'] = $tour->tour_id;
        // Khởi tạo số chỗ trống bằng sức chứa tối đa khi tạo mới
        $data['available_spots'] = $data['max_people'];

        DepartureSchedule::create($data);

        return redirect()->route('admin.tours.departure-schedules.index', $tour->tour_id)
            ->with('success', 'Tạo lịch xuất phát thành công');
    }

    public function edit($tourId, $scheduleId)
    {
        $tour = Tour::findOrFail($tourId);
        $schedule = DepartureSchedule::where('tour_id', $tour->tour_id)->findOrFail($scheduleId);
        $allowedStatuses = $schedule->availableStatusOptions();

        return view('admin.tours.departure_schedules.edit', compact('tour', 'schedule', 'allowedStatuses'));
    }

    public function update(Request $request, $tourId, $scheduleId)
    {
        $tour = Tour::findOrFail($tourId);
        $schedule = DepartureSchedule::where('tour_id', $tour->tour_id)->findOrFail($scheduleId);

        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_people' => 'required|integer|min:1',
            'meeting_point' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(array_keys(DepartureSchedule::STATUS_LABELS))],
            'notes' => 'nullable|string',
        ]);

        if (!$schedule->canTransitionTo($data['status'])) {
            return back()
                ->withErrors([
                    'status' => 'Chỉ được chuyển trạng thái theo từng bước: Đã lên lịch → Đang diễn ra → Hoàn thành. Chỉ các bước trước Hoàn thành mới được phép hủy, và khi đã hủy hoặc hoàn thành thì không thể quay lại.',
                ])
                ->withInput();
        }

        // Tính toán lại available_spots khi max_people thay đổi
        // Lấy số vé đã đặt (không tính đơn đã hủy)
        $bookedCount = \App\Models\Booking::where('schedule_id', $scheduleId)
            ->where('status', '!=', 'cancelled')
            ->sum('num_people');

        $data['available_spots'] = $data['max_people'] - $bookedCount;

        // Kiểm tra nếu max_people mới nhỏ hơn số vé đã đặt
        if ($data['available_spots'] < 0) {
            return back()->with('error', 'Không thể giảm sức chứa xuống dưới số lượng vé đã được đặt (' . $bookedCount . ' vé).')->withInput();
        }

        $schedule->update($data);

        return redirect()->route('admin.tours.departure-schedules.index', $tour->tour_id)
            ->with('success', 'Cập nhật lịch xuất phát thành công');
    }

    public function destroy($tourId, $scheduleId)
    {
        $tour = Tour::findOrFail($tourId);
        $schedule = DepartureSchedule::where('tour_id', $tour->tour_id)->findOrFail($scheduleId);
        $schedule->delete();

        return redirect()->route('admin.tours.departure-schedules.index', $tour->tour_id)
            ->with('success', 'Xóa lịch xuất phát thành công');
    }
}
