<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được cập nhật thành công.');
    }

    public function softDelete(Category $category)
    {
        $category->update(['status' => 'inactive']);
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được ẩn.');
    }
}