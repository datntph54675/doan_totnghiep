<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Support\Str;

class GuideController extends Controller
{
    /**
     * Display a listing of the guides.
     */
    public function index()
    {
        $guides = Guide::whereHas('user', function ($query) {
            $query->where('role', 'tour_guide');
        })
        ->with('user')
        ->orderBy('guide_id', 'desc')
        ->paginate(20);

        return view('admin.guide.index', compact('guides'));
    }

    /**
     * Show the form for creating a new guide.
     * @deprecated This functionality is disabled. Guides are now created through an approval process.
     */
    public function create()
    {
        // return view('admin.guide.create');
        // Logic commented out - not in use
    }

    /**
     * Store a newly created guide in storage.
     * @deprecated This functionality is disabled. Guides are now created through an approval process.
     */
    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'fullname' => 'required|string|max:255',
        //     'email' => 'required|email|unique:users,email',
        //     'phone' => 'nullable|string|max:20',
        //     'cccd' => 'nullable|string|max:20',
        //     'language' => 'nullable|string|max:255',
        //     'certificate' => 'nullable|string|max:255',
        //     'experience' => 'nullable|string',
        //     'specialization' => 'nullable|string|max:255',
        // ]);

        // // Make sure we create a unique username (required by users table)
        // $baseUsername = Str::before($validated['email'], '@');
        // $username = $baseUsername;
        // $i = 1;
        // while (User::where('username', $username)->exists()) {
        //     $username = $baseUsername . $i++;
        // }

        // $user = User::create([
        //     'username' => $username,
        //     'fullname' => $validated['fullname'],
        //     'email' => $validated['email'],
        //     'password' => bcrypt('password'),
        //     'role' => 'tour_guide',
        // ]);

        // Guide::create([
        //     'user_id' => $user->user_id,
        //     'phone' => $validated['phone'],
        //     'cccd' => $validated['cccd'],
        //     'language' => $validated['language'],
        //     'certificate' => $validated['certificate'],
        //     'experience' => $validated['experience'],
        //     'specialization' => $validated['specialization'],
        // ]);

        // return redirect()->route('admin.guides.index')->with('success', 'Tạo hướng dẫn viên thành công.');
    }

    /**
     * Display the specified guide.
     */
    public function show($id)
    {
        $guide = Guide::with('user')->findOrFail($id);
        return view('admin.guide.show', compact('guide'));
    }

    /**
     * Show the form for editing the specified guide.
     */
    public function edit($id)
    {
        $guide = Guide::with('user')->findOrFail($id);
        return view('admin.guide.edit', compact('guide'));
    }

    /**
     * Update the specified guide in storage.
     */
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
        ],[
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

    /**
     * Toggle the status of the specified guide.
     */
    public function toggleStatus($id)
    {
        $guide = Guide::findOrFail($id);

        $guide->status = $guide->status === 'active' ? 'inactive' : 'active';
        $guide->save();

        $message = $guide->status === 'active' ? 'Hiển thị hướng dẫn viên thành công.' : 'Ẩn hướng dẫn viên thành công.';
        return redirect()->route('admin.guides.index')->with('success', $message);
    }
}
