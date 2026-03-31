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

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'integer', 'exists:categories,category_id'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
            'available_only' => ['nullable', 'in:1'],
            'sort' => ['nullable', 'in:newest,price_asc,price_desc,duration_asc,duration_desc,name_asc'],
        ]);

        $query = Tour::visibleToUsers()->with('category');

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($tourQuery) use ($search) {
                $tourQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('supplier', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($filters['category'])) {
            $query->whereHas('category', function ($categoryQuery) use ($filters) {
                $categoryQuery->where('status', 'active')
                    ->where('category_id', $filters['category']);
            });
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['duration'])) {
            $query->where('duration', $filters['duration']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereHas('departureSchedules', function ($scheduleQuery) use ($filters) {
                $scheduleQuery->where('status', 'scheduled')
                    ->whereDate('start_date', '>=', $filters['start_date']);
            });
        }

        if (!empty($filters['available_only'])) {
            $query->whereHas('departureSchedules', function ($scheduleQuery) {
                $scheduleQuery->where('status', 'scheduled')
                    ->whereDate('start_date', '>', now()->toDateString())
                    ->where('available_spots', '>', 0);
            });
        }

        match ($filters['sort'] ?? 'newest') {
            'price_asc' => $query->orderBy('price')->orderByDesc('tour_id'),
            'price_desc' => $query->orderByDesc('price')->orderByDesc('tour_id'),
            'duration_asc' => $query->orderBy('duration')->orderByDesc('tour_id'),
            'duration_desc' => $query->orderByDesc('duration')->orderByDesc('tour_id'),
            'name_asc' => $query->orderBy('name')->orderByDesc('tour_id'),
            default => $query->orderByDesc('tour_id'),
        };

        $tours = $query->paginate(9)->withQueryString();
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
