<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::orderBy('tour_id', 'desc')->paginate(15);
        return view('admin.tour.index', compact('tours'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.tour.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:category,category_id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'policy' => 'nullable|string',
            'supplier' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'price' => 'nullable|numeric',
            'max_people' => 'nullable|integer',
            'duration' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive',
        ]);

        // handle uploaded image file
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tours', 'public');
            $data['image'] = $path;
        }

        $data['status'] = $data['status'] ?? 'active';
        Tour::create($data);

        return redirect()->route('admin.tours.index')->with('success', 'Tour created');
    }

    public function edit($id)
    {
        $tour = Tour::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('admin.tour.edit', compact('tour', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $data = $request->validate([
            'category_id' => 'nullable|exists:category,category_id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'policy' => 'nullable|string',
            'supplier' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'price' => 'nullable|numeric',
            'max_people' => 'nullable|integer',
            'duration' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            // delete old file if exists
            if ($tour->image && Storage::disk('public')->exists($tour->image)) {
                Storage::disk('public')->delete($tour->image);
            }
            $path = $request->file('image')->store('tours', 'public');
            $data['image'] = $path;
        }

        $tour->update($data);
        return redirect()->route('admin.tours.index')->with('success', 'Tour updated');
    }

    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);
        $tour->delete();
        return redirect()->route('admin.tours.index')->with('success', 'Tour deleted');
    }
}
