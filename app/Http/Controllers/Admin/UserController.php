<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the users with role 'user'.
     */
    public function index()
    {
        // Use the correct primary key column name for ordering.
        $users = User::where('role', 'user')->orderBy('user_id', 'desc')->paginate(20);
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'role' => 'required|in:user,admin,tour_guide',
        ]);

        $user->fullname = $validated['fullname'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->save();

        return redirect(url('admin/users'))->with('success', 'Cập nhật người dùng thành công.');
    }
}
