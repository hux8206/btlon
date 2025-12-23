<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function admin()
    {
        $users = User::paginate(3);
        return view('admin.index', compact('users'));
    }

    public function status($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Không thể khóa tài khoản admin!');
        }

        // Đảo ngược trạng thái (True thành False, False thành True)
        $user->status = !$user->status;
        $user->save();

        $statusMessage = $user->status ? 'Đã kích hoạt tài khoản!' : 'Đã vô hiệu hóa tài khoản!';

        return redirect()->back()->with('success', $statusMessage);
    }

    public function tests()
    {
        $tests = DB::table('test')->paginate(5);
        return view('admin.tests', compact('tests'));
    }

    public function deleteTest($id)
    {
        DB::table('test')->where('testID', $id)->delete();
        return redirect()->back()->with('success', 'Delete success !');
    }

    public function manage($id)
    {
        // $userID = Auth::id(); // Admin không cần dòng này để check quyền sở hữu

        // 1. SỬA LẠI: Chỉ tìm theo testID (Bỏ check userID vì Admin được xem hết)
        $test = DB::table('test')->where('testID', $id)->first();

        // Nếu không tìm thấy bài thi thì quay lại trang danh sách
        if (!$test) {
            // Lưu ý: Đảm bảo route 'admin.tests' (hoặc tên route danh sách bài thi của bạn) tồn tại
            return redirect()->route('admin.tests')->with('error', 'Bài thi không tồn tại.');
        }

        // 2. Lấy danh sách người đã chơi bài này
        $playerHistory = DB::table('history')
            ->join('users', 'history.userID', '=', 'users.userID')
            ->where('history.testID', $id)
            ->select('users.fullName', 'history.correct_question', 'history.question_completed', 'history.done_at', 'history.numOfPlay')
            ->orderBy('history.done_at', 'desc')
            ->get();

        return view('admin.show', compact('test', 'playerHistory'));
    }

    public function statistic()
    {
        // 1. CÁC SỐ LIỆU TỔNG QUAN
        $totalUsers = DB::table('users')->count();
        $totalTests = DB::table('test')->count();
        $totalPlays = DB::table('history')->count();

        // User mới đăng ký trong tháng nà

        // 2. DỮ LIỆU BIỂU ĐỒ (Số bài thi tạo ra trong 7 ngày gần nhất)
        $chartData = [];
        $chartLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('d/m'); // Nhãn ngày (VD: 20/10)

            $count = DB::table('test')
                ->whereDate('dayCreated', $date->toDateString()) // Lưu ý: Cột ngày tạo của bạn là 'dayCreated'
                ->count();

            $chartData[] = $count;
        }

        // 3. TOP 5 NGƯỜI CHƠI TÍCH CỰC (Đã làm ở câu trước, tái sử dụng)
        $topUsers = DB::table('users')
            ->leftJoin('history', 'users.userID', '=', 'history.userID')
            ->select('users.fullName', 'users.email', DB::raw('COUNT(history.historyID) as total_played'))
            ->groupBy('users.userID', 'users.fullName', 'users.email')
            ->orderBy('total_played', 'desc')
            ->limit(5)
            ->get();

        return view('admin.statistic', compact(
            'totalUsers',
            'totalTests',
            'totalPlays',
            'chartLabels',
            'chartData',
            'topUsers'
        ));
    }
}
