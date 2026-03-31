<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Category;
use App\Services\TourAvailabilityService;
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
            'category_id' => 'nullable|exists:categories,category_id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'policy' => 'nullable|string',
            'supplier' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
        ], [
            'category_id.exists' => 'Danh mục được chọn không hợp lệ.',
            'name.required' => 'Tên tour là bắt buộc.',
            'name.string' => 'Tên tour phải là chuỗi ký tự.',
            'name.max' => 'Tên tour không được vượt quá :max ký tự.',
            'description.string' => 'Mô tả tour phải là chuỗi ký tự.',
            'policy.string' => 'Chính sách tour phải là chuỗi ký tự.',
            'supplier.string' => 'Nhà cung cấp phải là chuỗi ký tự.',
            'supplier.max' => 'Nhà cung cấp không được vượt quá :max ký tự.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Hình ảnh không được lớn hơn :max kilobytes.',
            'price.required' => 'Giá tour là bắt buộc.',
            'price.numeric' => 'Giá tour phải là số.',
            'price.min' => 'Giá tour không được nhỏ hơn :min.',
            'duration.integer' => 'Thời lượng tour phải là số nguyên.',
            'duration.min' => 'Thời lượng tour phải ít nhất :min ngày.',
            'status.required' => 'Trạng thái tour là bắt buộc.',
            'status.in' => 'Trạng thái tour không hợp lệ.',
        ], [
            'category_id' => 'Danh mục',
            'name' => 'Tên tour',
            'description' => 'Mô tả',
            'policy' => 'Chính sách',
            'supplier' => 'Nhà cung cấp',
            'image' => 'Hình ảnh',
            'price' => 'Giá tour',
            'duration' => 'Thời lượng',
            'status' => 'Trạng thái',
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

        return redirect()->route('admin.tours.index')->with('success', 'Tạo tour thành công.');
    }

    public function edit($id)
    {
        $tour = Tour::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('admin.tours.edit', compact('tour', 'categories'));
    }

    public function show($id)
    {
        $tour = Tour::with(['category', 'itineraries', 'departureSchedules'])->findOrFail($id);
        return view('admin.tours.show', compact('tour'));
    }

    public function departureSchedules($id)
    {
        app(TourAvailabilityService::class)->sync();

        $tour = Tour::with(['departureSchedules.guideAssignments.guide.user'])->findOrFail($id);
        return view('admin.tours.departure_schedules.index', compact('tour'));
    }

    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        // Không cho phép chỉnh sửa tour nếu đã có booking hoặc lịch trình
        if ($tour->bookings()->exists() || $tour->itineraries()->exists()) {
            return redirect()->route('admin.tours.index')->with('error', 'Không thể chỉnh sửa tour vì đã có đơn đặt hoặc lịch trình.');
        }

        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,category_id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'policy' => 'nullable|string',
            'supplier' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
        ], [
            'category_id.exists' => 'Danh mục được chọn không hợp lệ.',
            'name.required' => 'Tên tour là bắt buộc.',
            'name.string' => 'Tên tour phải là chuỗi ký tự.',
            'name.max' => 'Tên tour không được vượt quá :max ký tự.',
            'description.string' => 'Mô tả tour phải là chuỗi ký tự.',
            'policy.string' => 'Chính sách tour phải là chuỗi ký tự.',
            'supplier.string' => 'Nhà cung cấp phải là chuỗi ký tự.',
            'supplier.max' => 'Nhà cung cấp không được vượt quá :max ký tự.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Hình ảnh không được lớn hơn :max kilobytes.',
            'price.required' => 'Giá tour là bắt buộc.',
            'price.numeric' => 'Giá tour phải là số.',
            'price.min' => 'Giá tour không được nhỏ hơn :min.',
            'duration.integer' => 'Thời lượng tour phải là số nguyên.',
            'duration.min' => 'Thời lượng tour phải ít nhất :min ngày.',
            'status.required' => 'Trạng thái tour là bắt buộc.',
            'status.in' => 'Trạng thái tour không hợp lệ.',
        ], [
            'category_id' => 'Danh mục',
            'name' => 'Tên tour',
            'description' => 'Mô tả',
            'policy' => 'Chính sách',
            'supplier' => 'Nhà cung cấp',
            'image' => 'Hình ảnh',
            'price' => 'Giá tour',
            'duration' => 'Thời lượng',
            'status' => 'Trạng thái',
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
        return redirect()->route('admin.tours.index')->with('success', 'Cập nhật tour thành công.');
    }

    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);
        $tour->delete();
        return redirect()->route('admin.tours.index')->with('success', 'Xóa tour thành công.');
    }
}
