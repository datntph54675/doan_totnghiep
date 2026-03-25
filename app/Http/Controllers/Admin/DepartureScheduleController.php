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
