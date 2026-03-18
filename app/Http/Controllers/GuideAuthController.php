<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GuideAuthController extends Controller
{
    public function showLogin()
    {
        return view('guide.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user && $user->isGuide()) {
                return redirect()->intended('/guide/dashboard');
            }

            Auth::logout();
            return back()->withErrors(['username' => 'Tài khoản không có quyền guide.']);
        }

        return back()->withErrors(['username' => 'Tên đăng nhập hoặc mật khẩu không đúng.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/guide/login');
    }

    public function dashboard()
    {
        return view('guide.dashboard');
    }
}
