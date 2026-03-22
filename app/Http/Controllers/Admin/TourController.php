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
        return view('admin.tours.index', compact('tours'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.tours.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'nullable',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'policy' => 'nullable|string',
            'supplier' => 'nullable|string',
            'image' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_people' => 'required|integer|min:0',
            'duration' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        // Convert empty string to null for category_id
        if (empty($data['category_id'])) {
            $data['category_id'] = null;
        }

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
        return view('admin.tours.edit', compact('tour', 'categories'));
    }

    public function show($id)
    {
        $tour = Tour::findOrFail($id);
        return view('admin.tours.show', compact('tour'));
    }

    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $data = $request->validate([
            'category_id' => 'nullable',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'policy' => 'nullable|string',
            'supplier' => 'nullable|string',
            'image' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_people' => 'required|integer|min:0',
            'duration' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        // Convert empty string to null for category_id
        if (empty($data['category_id'])) {
            $data['category_id'] = null;
        }

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
