<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Category;
use App\Services\TourAvailabilityService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        app(TourAvailabilityService::class)->sync();

        $featuredTours = Tour::visibleToUsers()
            ->with('category')
            ->orderBy('tour_id', 'desc')
            ->take(8)
            ->get();

        $categories = Category::where('status', 'active')->orderBy('name')->get();

        $stats = [
            'tours'     => Tour::visibleToUsers()->count(),
            'customers' => \App\Models\Customer::count(),
            'tours_all' => Tour::count(),
        ];

        return view('welcome', compact('featuredTours', 'categories', 'stats'));
    }
}
