<?php

namespace App\Http\Controllers;

use App\Models\User;
    
use App\Http\Requests\RegisterRequest;
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
            'pass' => $request->get('password'),
            'fullName'=> $request->get('fullname')
        ]);
    }
}
