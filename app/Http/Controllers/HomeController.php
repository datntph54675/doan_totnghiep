<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredTours = Tour::where('status', 'active')
            ->with('category')
            ->orderBy('tour_id', 'desc')
            ->take(8)
            ->get();

        $categories = Category::orderBy('name')->get();

        $stats = [
            'tours' => Tour::where('status', 'active')->count(),
            'customers' => \App\Models\Customer::count(),
            'tours_all' => Tour::count(),
        ];

        return view('welcome', compact('featuredTours', 'categories', 'stats'));
    }
}
