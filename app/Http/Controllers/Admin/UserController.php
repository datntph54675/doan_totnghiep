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
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('fullname', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_blacklisted')) {
            $query->where('is_blacklisted', (bool) $request->is_blacklisted);
        }

        $users = $query->orderBy('user_id', 'desc')->paginate(20)->withQueryString();

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
            'fullname' => 'required|string|max:255|regex:/\S/',
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
        ], [
            'fullname.required' => 'Họ tên là bắt buộc.',
            'fullname.max' => 'Họ tên không được vượt quá 255 ký tự.',
            'fullname.regex' => 'Họ tên không được chỉ chứa khoảng trắng.',

            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email này đã được sử dụng.',
        ]);

        $user->fullname = trim($validated['fullname']);
        $user->email = trim($validated['email']);
        $user->save();

        return redirect(url('admin/users'))->with('success', 'Cập nhật người dùng thành công.');
    }

    /**
     * Toggle the status of the specified user.
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        $message = $user->status === 'active' ? 'Hiển thị người dùng thành công.' : 'Ẩn người dùng thành công.';
        return redirect(url('admin/users'))->with('success', $message);
    }

    /**
     * Toggle the blacklist status of the specified user.
     */
    public function toggleBlacklist($id)
    {
        $user = User::findOrFail($id);

        $user->is_blacklisted = !$user->is_blacklisted;
        $user->save();

        $message = $user->is_blacklisted
            ? "Đã đưa người dùng {$user->fullname} vào Blacklist."
            : "Đã gỡ người dùng {$user->fullname} khỏi Blacklist.";

        return redirect(url('admin/users'))->with('success', $message);
    }
}
