<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register()
    {
        return view('users.register');
    }

    public function postRegister(RegisterRequest $request)
    {
        User::create([
            'email' => $request->get('email'),
            'pass' => Hash::make($request->get('password')),
            'fullName' => $request->get('fullname')
        ]);
        return back()->with('message', 'Dang Ky Thanh Cong !');
    }

    public function login()
    {
        return view('users.login');
    }

    public function postLogin(LoginRequest $request)
    {
        $cre = $request->only('email', 'password');

        // BƯỚC 1: Chỉ kiểm tra Email và Password (Bỏ 'status' => 1 ra khỏi đây)
        if (Auth::attempt($cre)) {

            // BƯỚC 2: Đăng nhập thành công rồi, giờ mới lấy User ra kiểm tra Status
            $user = Auth::user();

            // Kiểm tra nếu tài khoản bị khóa (Ví dụ status 0 là khóa, 1 là hoạt động)
            // Nếu database của bạn: 1 là active, 0 là block -> thì check != 1 hoặc == 0
            if ($user->status != 1) {

                // Tài khoản bị khóa -> Đăng xuất ngay lập tức
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Trả về thông báo lỗi: Tài khoản bị khóa
                return back()->with('unvalid', 'Tài khoản của bạn đã bị vô hiệu hóa!');
            }

            // BƯỚC 3: Nếu Status OK (bằng 1) thì cho đi tiếp
            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->route('admin');
            }

            return redirect()->intended('home');
        }

        // BƯỚC 4: Nếu Auth::attempt trả về False -> Tức là Sai Email hoặc Sai Mật khẩu
        return back()->with([
            'unvalid' => 'Email hoặc mật khẩu không chính xác!'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerate();

        return redirect()->route('start');
    }

    public function start()
    {
        return view('start');
    }
}
