<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Support\Str;

class GuideController extends Controller
{

    public function index(Request $request)
    {
        $query = Guide::whereHas('user', function ($q) {
            $q->where('role', 'tour_guide');
        })->with('user');

        if ($request->filled('keyword')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('fullname', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $guides = $query->orderBy('guide_id', 'desc')->paginate(20)->withQueryString();

        return view('admin.guide.index', compact('guides'));
    }


    public function create()
    {
        return view('admin.guide.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users,email', 'regex:/^[A-Za-z0-9._%+-]+@gmail\.com$/'],
            'password' => 'required|string|min:6|confirmed|max:20',
            'password_confirmation' => 'required|string|min:6|max:20',
            'phone' => 'required|string|max:20',
            'cccd' => 'required|string|max:20',
            'language' => 'required|string|max:255',
            'certificate' => 'required|string|max:255',
            'experience' => 'required|string',
            'specialization' => 'required|string|max:255',
        ], [
            'fullname.required' => 'Họ tên là bắt buộc.',
            'fullname.max' => 'Họ tên không được vượt quá 255 ký tự.',

            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            'email.regex' => 'Email phải là địa chỉ Gmail.',

            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.max' => 'Mật khẩu không được vượt quá 20 ký tự.',

            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'phone.required' => 'Số điện thoại là bắt buộc.',

            'cccd.max' => 'CCCD không được vượt quá 20 ký tự.',
            'cccd.required' => 'CCCD là bắt buộc.',

            'language.max' => 'Ngôn ngữ không được vượt quá 255 ký tự.',
            'language.required' => 'Ngôn ngữ là bắt buộc.',

            'certificate.max' => 'Chứng chỉ không được vượt quá 255 ký tự.',
            'certificate.required' => 'Chứng chỉ là bắt buộc.',

            'specialization.max' => 'Chuyên môn không được vượt quá 255 ký tự.',
            'specialization.required' => 'Chuyên môn là bắt buộc.',

            'experience.required' => 'Kinh nghiệm là bắt buộc.',
        ]);

        // Make sure we create a unique username (required by users table)
        $baseUsername = Str::before($validated['email'], '@');
        $username = $baseUsername;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $i++;
        }

        $user = User::create([
            'username' => $username,
            'fullname' => $validated['fullname'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'tour_guide',
        ]);

        Guide::create([
            'user_id' => $user->user_id,
            'cccd' => $validated['cccd'] ?? null,
            'language' => $validated['language'] ?? null,
            'certificate' => $validated['certificate'] ?? null,
            'experience' => $validated['experience'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
        ]);

        return redirect()->route('admin.guides.index')->with('success', 'Tạo hướng dẫn viên thành công.');
    }

    public function show($id)
    {
        $guide = Guide::with('user')->findOrFail($id);
        return view('admin.guide.show', compact('guide'));
    }


    public function edit($id)
    {
        $guide = Guide::with('user')->findOrFail($id);
        return view('admin.guide.edit', compact('guide'));
    }


    public function update(Request $request, $id)
    {
        $guide = Guide::findOrFail($id);
        $user = $guide->user;

        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'phone' => 'nullable|string|max:20',
            'cccd' => 'nullable|string|max:20',
            'language' => 'nullable|string|max:255',
            'certificate' => 'nullable|string|max:255',
            'experience' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
        ], [
            'fullname.required' => 'Họ tên là bắt buộc.',
            'fullname.max' => 'Họ tên không được vượt quá 255 ký tự.',

            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email này đã được sử dụng.',

            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',

            'cccd.max' => 'CCCD không được vượt quá 20 ký tự.',

            'language.max' => 'Ngôn ngữ không được vượt quá 255 ký tự.',

            'certificate.max' => 'Chứng chỉ không được vượt quá 255 ký tự.',

            'specialization.max' => 'Chuyên môn không được vượt quá 255 ký tự.',
        ]);

        $user->update([
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        // Update guide information
        $guide->update([
            'cccd' => $validated['cccd'],
            'language' => $validated['language'],
            'certificate' => $validated['certificate'],
            'experience' => $validated['experience'],
            'specialization' => $validated['specialization'],
        ]);

        return redirect()->route('admin.guides.index')->with('success', 'Cập nhật hướng dẫn viên thành công.');
    }

    public function toggleStatus($id)
    {
        $guide = Guide::findOrFail($id);

        $guide->status = $guide->status === 'active' ? 'inactive' : 'active';
        $guide->save();

        $message = $guide->status === 'active' ? 'Hiển thị hướng dẫn viên thành công.' : 'Ẩn hướng dẫn viên thành công.';
        return redirect()->route('admin.guides.index')->with('success', $message);
    }
}
