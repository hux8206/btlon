<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    public function admin()
    {
        $users = User::paginate(3);
        return view('admin.index',compact('users'));
    }

    public function status($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Không thể khóa tài khoản admin!');
        }

        // Đảo ngược trạng thái (True thành False, False thành True)
        $user->status = !$user-> status;
        $user->save();

        $statusMessage = $user->status ? 'Đã kích hoạt tài khoản!' : 'Đã vô hiệu hóa tài khoản!';

        return redirect()->back()->with('success', $statusMessage);
    }

    public function tests()
    {
        $tests = DB::table('test')-> paginate(5);
        return view('admin.tests',compact('tests'));
    }
}
