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
     */
    public function create()
    {
        return view('admin.guide.create');
    }

    /**
     * Store a newly created guide in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'cccd' => 'nullable|string|max:20',
            'language' => 'nullable|string|max:255',
            'certificate' => 'nullable|string|max:255',
            'experience' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
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
            'email' => $validated['email'],
            'password' => bcrypt('password'),
            'role' => 'tour_guide',
        ]);

        Guide::create([
            'user_id' => $user->user_id,
            'cccd' => $validated['cccd'],
            'language' => $validated['language'],
            'certificate' => $validated['certificate'],
            'experience' => $validated['experience'],
            'specialization' => $validated['specialization'],
        ]);

        return redirect()->route('admin.guides.index')->with('success', 'Tạo hướng dẫn viên thành công.');
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

        $validated = $request->validate([
            'cccd' => 'nullable|string|max:20',
            'language' => 'nullable|string|max:255',
            'certificate' => 'nullable|string|max:255',
            'experience' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
        ]);

        $guide->update($validated);

        return redirect()->route('admin.guides.index')->with('success', 'Cập nhật hướng dẫn viên thành công.');
    }

    /**
     * Remove the specified guide from storage.
     */
    public function destroy($id)
    {
        $guide = Guide::findOrFail($id);
        $guide->delete();

        return redirect()->route('admin.guides.index')->with('success', 'Xóa hướng dẫn viên thành công.');
    }
}