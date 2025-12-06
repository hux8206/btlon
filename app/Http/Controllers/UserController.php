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
            'fullName'=> $request->get('fullname')
        ]);
        return back()->with('message','Dang Ky Thanh Cong !');
    }

    public function login()
    {
        return view('users.login');
    }

    public function postLogin(LoginRequest $request)
    {
        $cre = $request->only('email','password');

        if(Auth::attempt($cre)){
            $request -> session() -> regenerate();
            if(Auth::user()->role === 'admin'){
                return redirect()->route('admin');
            }
            return redirect()->intended('home');
        }
        return back()->withErrors([
            'password' => 'The password is wrong !'
        ]);

    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request -> session() -> invalidate();

        $request -> session() -> regenerate();

        return redirect()->route('start');
    }

    public function start()
    {
        return view('start');
    }

    public function profile()
    {
        return view('users.profile');
    }
}
