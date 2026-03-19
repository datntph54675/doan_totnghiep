<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\DepartureSchedule;
use App\Models\Itinerary;

class UserTourController extends Controller
{
    public function index()
    {
        $tours = Tour::with('category')
            ->where('status', 'active')
            ->orderBy('tour_id', 'desc')
            ->paginate(12);

        return view('user.tours', compact('tours'));
    }

    public function show($id)
    {
        $tour = Tour::with(['category', 'itineraries'])
            ->where('status', 'active')
            ->findOrFail($id);

        $schedules = DepartureSchedule::where('tour_id', $id)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get();

        $itineraries = Itinerary::where('tour_id', $id)
            ->orderBy('day_number', 'asc')
            ->get();

        return view('user.tour-detail', compact('tour', 'schedules', 'itineraries'));
    }
}
