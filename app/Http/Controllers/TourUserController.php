<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use App\Models\Category;
use App\Models\DepartureSchedule;
use App\Services\TourAvailabilityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TourUserController extends Controller
{
    public function index(Request $request)
    {
        app(TourAvailabilityService::class)->sync();

        $query = Tour::visibleToUsers()->with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($categoryQuery) use ($request) {
                $categoryQuery->where('status', 'active')
                    ->where('category_id', $request->category);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('duration')) {
            $query->where('duration', $request->duration);
        }

        $tours = $query->orderBy('tour_id', 'desc')->paginate(9)->withQueryString();
        $categories = Category::where('status', 'active')->orderBy('name')->get();

        return view('tours.index', compact('tours', 'categories'));
    }

    public function show($id)
    {
        app(TourAvailabilityService::class)->sync();

        $tour = Tour::visibleToUsers()
            ->with(['category', 'itineraries', 'departureSchedules'])
            ->find($id);

        if (!$tour) {
            $userId = Auth::id();

            $hasBookedTour = $userId
                ? Booking::where('user_id', $userId)
                    ->where('tour_id', $id)
                    ->where('status', '!=', 'cancelled')
                    ->exists()
                : false;

            abort_unless($hasBookedTour, 404);

            $tour = Tour::with(['category', 'itineraries', 'departureSchedules'])
                ->findOrFail($id);
        }

        $schedules = DepartureSchedule::where('tour_id', $id)
            ->whereIn('status', ['scheduled'])
            ->whereDate('start_date', '>', now()->toDateString())
            ->orderBy('start_date')
            ->get();

        $relatedTours = Tour::visibleToUsers()
            ->where('category_id', $tour->category_id)
            ->where('tour_id', '!=', $id)
            ->take(4)
            ->get();

        return view('tours.show', compact('tour', 'schedules', 'relatedTours'));
    }
}
