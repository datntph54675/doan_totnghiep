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
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'name.max' => 'Tên danh mục không được vượt quá :max ký tự.',
            'description.string' => 'Mô tả danh mục phải là chuỗi ký tự.',
        ], [
            'name' => 'Tên danh mục',
            'description' => 'Mô tả danh mục',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Tạo danh mục thành công.');
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
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'name.max' => 'Tên danh mục không được vượt quá :max ký tự.',
            'description.string' => 'Mô tả danh mục phải là chuỗi ký tự.',
        ], [
            'name' => 'Tên danh mục',
            'description' => 'Mô tả danh mục',
        ]);

        // Nếu danh mục đã có tour liên kết thì không cho phép chỉnh sửa bất kỳ trường nào
        if ($category->tours()->exists()) {
            return redirect()->back()->withInput()->with('error', 'Không thể chỉnh sửa danh mục vì đã có tour liên kết.');
        }

        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công.');
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
