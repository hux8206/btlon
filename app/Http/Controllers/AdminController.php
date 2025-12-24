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
        $test = DB::table('test')->where('testID', $id)->first();

        if (!$test) {
            return redirect()->route('admin.tests')->with('error', 'Bài thi không tồn tại.');
        }

        $playerHistory = DB::table('history')
            ->join('users', 'history.userID', '=', 'users.userID')
            ->where('history.testID', $id)
            ->select(
                'users.fullName',
                'history.correct_question',
                'history.question_completed',
                'history.done_at',
                'history.numOfPlay'
            )
            ->orderBy('history.done_at', 'desc')
            ->get();

        return view('admin.show', compact('test', 'playerHistory'));
    }

    public function statistic()
    {
        $totalUsers = DB::table('users')->count();
        $totalTests = DB::table('test')->count();
        $totalPlays = DB::table('history')->count();

        $chartData = [];
        $chartLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('d/m');

            $count = DB::table('test')
                ->whereDate('dayCreated', $date->toDateString())
                ->count();

            $chartData[] = $count;
        }

        $topUsers = DB::table('users')
            ->leftJoin('history', 'users.userID', '=', 'history.userID')
            ->select(
                'users.fullName',
                'users.email',
                DB::raw('COUNT(history.historyID) as total_played')
            )
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
