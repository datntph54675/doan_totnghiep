<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Kiểm tra xem user có tồn tại không dựa trên google_id
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // Nếu email đã tồn tại, liên kết với Google ID
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Cập nhật google_id cho user hiện có
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                } else {
                    // Tạo user mới
                    $user = User::create([
                        'fullname' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'username' => str_replace(['@gmail.com', '@'], ['', ''], $googleUser->email) . '_' . time(),
                        'phone' => '',
                        'password' => Hash::make(uniqid()),
                        'role' => 'user',
                        'status' => 'active',
                    ]);
                }
            } else {
                // Cập nhật avatar nếu cần
                if ($googleUser->avatar) {
                    $user->update(['avatar' => $googleUser->avatar]);
                }
            }

            Auth::login($user, true);

            return redirect()->route('home')->with('success', 'Đăng nhập bằng Google thành công!');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
