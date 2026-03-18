<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Category;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::with('category')->get();
        return view('tours.index', compact('tours'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('tours.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:category,category_id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'policy' => 'nullable|string',
            'supplier' => 'nullable|string',
            'image' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_people' => 'required|integer|min:0',
            'duration' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        Tour::create($request->all());
        return redirect()->route('tours.index')->with('success', 'Tour created successfully.');
    }

    public function show(Tour $tour)
    {
        $tour->load('category');
        return view('tours.show', compact('tour'));
    }

    public function edit(Tour $tour)
    {
        $categories = Category::all();
        return view('tours.edit', compact('tour', 'categories'));
    }

    public function update(Request $request, Tour $tour)
    {
        $request->validate([
            'category_id' => 'nullable|exists:category,category_id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'policy' => 'nullable|string',
            'supplier' => 'nullable|string',
            'image' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_people' => 'required|integer|min:0',
            'duration' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        $tour->update($request->all());
        return redirect()->route('tours.index')->with('success', 'Tour cập nhật thành công.');
    }

    public function softDelete(Tour $tour)
    {
        $tour->update(['status' => 'inactive']);
        return redirect()->route('tours.index')->with('success', 'Tour đã được ẩn.');
    }
}