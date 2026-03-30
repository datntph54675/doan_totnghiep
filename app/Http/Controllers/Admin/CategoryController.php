<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   public function index()
    {
        $categories = Category::all();
        // Thêm thông tin có tour hay không cho mỗi category
        foreach ($categories as $category) {
            $category->has_tours = $category->tours()->exists();
        }
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được ẩn.');
    }

    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $category->has_tours = $category->tours()->exists();
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Kiểm tra nếu danh mục có tour thì không cho phép thay đổi status
        if ($category->tours()->exists() && $request->status != $category->status) {
            return redirect()->back()->with('error', 'Không thể thay đổi trạng thái danh mục đã có tour.');
        }

        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật.');
    }

    public function destroy(Category $category)
    {
        // Kiểm tra nếu danh mục có tour thì không cho phép ẩn
        if ($category->tours()->exists()) {
            return redirect()->back()->with('error', 'Không thể ẩn danh mục này vì đã có tour liên kết.');
        }

        $category->update(['status' => 'inactive']);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được ẩn.');
    }

    public function unhide(Category $category)
    {
        $category->update(['status' => 'active']);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được hiện.');
    }
}
