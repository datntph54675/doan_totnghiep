<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepartureSchedule;
use App\Models\Tour;
use Illuminate\Http\Request;

class DepartureScheduleController extends Controller
{
    public function create($tourId)
    {
        $tour = Tour::findOrFail($tourId);

        return view('admin.tours.departure_schedules.create', compact('tour'));
    }

    public function store(Request $request, $tourId)
    {
        $tour = Tour::findOrFail($tourId);

        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_people' => 'required|integer|min:1',
            'meeting_point' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'notes' => 'nullable|string',
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

        return view('admin.tours.departure_schedules.edit', compact('tour', 'schedule'));
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
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

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
