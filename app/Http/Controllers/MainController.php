<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MainController extends Controller
{
    public function home()
    {
        $latestTests = DB::table('test')
            ->leftJoin('history', 'test.testID', '=', 'history.testID')
            ->select(
                'test.*',
                DB::raw('COUNT(history.historyID) as play_count')
            )
            ->groupBy(
                'test.testID',
                'test.title',
                'test.timeEachQuestion',
                'test.quantity',
                'test.mode',
                'test.userID',
                'test.dayCreated'
            )
            ->orderBy('test.dayCreated', 'desc')
            ->limit(3)
            ->get();

        return view('layout.home', compact('latestTests'));
    }

    public function history(Request $request)
    {
        $userID = Auth::id();
        $keyword = $request->get('keyword');

        $query = DB::table('history')
            ->join('test', 'history.testID', '=', 'test.testID')
            ->where('history.userID', $userID)
            ->select(
                'history.*',
                'test.title as test_title',
                'test.quantity as total_questions'
            );

        if ($keyword) {
            $query->where('test.title', 'like', '%' . $keyword . '%');
        }

        $histories = $query->orderBy('history.done_at', 'desc')->paginate(10);

        return view('layout.history', compact('histories'));
    }

    public function clearHistory()
    {
        $userID = Auth::id();

        DB::table('history')->where('userID', $userID)->delete();

        return redirect()->back()->with('success', 'Đã xóa toàn bộ lịch sử ôn tập.');
    }

    public function explore(Request $request)
    {
        $keyword = $request->get('keyword');

        $query = DB::table('test')
            ->join('users', 'test.userID', '=', 'users.userID')
            ->leftJoin('history', 'test.testID', '=', 'history.testID')
            ->select(
                'test.*',
                'users.fullName as author_name',
                DB::raw('COUNT(history.historyID) as play_count')
            )
            ->where('test.mode', 0)
            ->groupBy(
                'test.testID',
                'test.title',
                'test.dayCreated',
                'test.quantity',
                'test.mode',
                'test.userID',
                'test.timeEachQuestion',
                'users.fullName'
            );

        if ($keyword) {
            $query->where('test.title', 'like', '%' . $keyword . '%');
        }

        $tests = $query->orderBy('test.dayCreated', 'desc')->paginate(9);

        return view('layout.explore', compact('tests'));
    }

    public function private(Request $request)
    {
        $inputID = $request->get('private_id');

        $test = DB::table('test')->where('testID', $inputID)->first();

        if ($test) {
            return redirect()->route('joinTest', ['id' => $test->testID]);
        }

        return redirect()->back()->with('error', 'Không tìm thấy bài Test với ID: #' . $inputID);
    }

    public function favourites()
    {
        $userID = Auth::id();

        $favorites = DB::table('favourites')
            ->join('test', 'favourites.testID', '=', 'test.testID')
            ->join('users', 'test.userID', '=', 'users.userID')
            ->where('favourites.userID', $userID)
            ->select(
                'test.*',
                'users.fullName as author_name',
                'favourites.created_at as favorited_at'
            )
            ->orderBy('favourites.created_at', 'desc')
            ->get();

        return view('layout.favourite', compact('favorites'));
    }

    public function statistic()
    {
        $userID = Auth::id();

        $totalTestsDone = DB::table('history')->where('userID', $userID)->count();

        $stats = DB::table('history')
            ->where('userID', $userID)
            ->select(
                DB::raw('SUM(correct_question) as total_correct'),
                DB::raw('SUM(question_completed) as total_attempted')
            )
            ->first();

        $totalCorrect = $stats->total_correct ?? 0;
        $totalAttempted = $stats->total_attempted ?? 0;
        $averageAccuracy = ($totalAttempted > 0)
            ? round(($totalCorrect / $totalAttempted) * 100, 1)
            : 0;

        $totalFavorites = DB::table('favourites')->where('userID', $userID)->count();

        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(6);

        $activityData = DB::table('history')
            ->select(DB::raw('DATE(done_at) as date'), DB::raw('count(*) as count'))
            ->where('userID', $userID)
            ->whereBetween('done_at', [
                $startDate->format('Y-m-d 00:00:00'),
                $endDate->format('Y-m-d 23:59:59')
            ])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $displayDate = Carbon::now()->subDays($i)->format('d/m');

            $dayData = $activityData->firstWhere('date', $date);

            $chartLabels[] = $displayDate;
            $chartData[] = $dayData ? $dayData->count : 0;
        }

        $totalWrong = $totalAttempted - $totalCorrect;
        $accuracyChartData = [$totalCorrect, $totalWrong];

        $recentActivity = DB::table('history')
            ->join('test', 'history.testID', '=', 'test.testID')
            ->where('history.userID', $userID)
            ->select(
                'test.title',
                'history.correct_question',
                'history.question_completed',
                'history.done_at'
            )
            ->orderBy('history.done_at', 'desc')
            ->limit(5)
            ->get();

        return view('layout.statistic', compact(
            'totalTestsDone',
            'averageAccuracy',
            'totalFavorites',
            'chartLabels',
            'chartData',
            'accuracyChartData',
            'recentActivity'
        ));
    }
}
