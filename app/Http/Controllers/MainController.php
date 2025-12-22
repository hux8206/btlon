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
                DB::raw('COUNT(history.historyID) as play_count') // Đếm số lần chơi
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
            ->orderBy('test.dayCreated', 'desc') // Sắp xếp mới nhất lên đầu
            ->limit(3) // Lấy 6 bài mới nhất
            ->get();
        return view('layout.home', compact('latestTests'));
    }

    // Trong TestController.php

    public function history(Request $request)
    {
        $userID = Auth::id();
        $keyword = $request->get('keyword'); // Lấy từ khóa từ thanh tìm kiếm

        // Bắt đầu truy vấn
        $query = DB::table('history')
            ->join('test', 'history.testID', '=', 'test.testID')
            ->where('history.userID', $userID)
            ->select(
                'history.*',
                'test.title as test_title',
                'test.quantity as total_questions'
            );

        // NẾU CÓ TỪ KHÓA TÌM KIẾM -> Lọc theo tên bài test
        if ($keyword) {
            $query->where('test.title', 'like', '%' . $keyword . '%');
        }

        // Sắp xếp và lấy dữ liệu
        $histories = $query->orderBy('history.done_at', 'desc')->paginate(10);

        return view('layout.history', compact('histories'));
    }

    public function clearHistory()
    {
        $userID = Auth::id();

        // Xóa tất cả lịch sử của user hiện tại
        DB::table('history')->where('userID', $userID)->delete();

        return redirect()->back()->with('success', 'Đã xóa toàn bộ lịch sử ôn tập.');
    }

    public function explore(Request $request)
    {
        $keyword = $request->get('keyword'); // Từ khóa tìm kiếm bài Public

        // Query cơ bản: Lấy bài test + đếm số lượt chơi
        $query = DB::table('test')
            ->join('users', 'test.userID', '=', 'users.userID')
            ->leftJoin('history', 'test.testID', '=', 'history.testID')
            ->select(
                'test.*',
                'users.fullName as author_name',
                DB::raw('COUNT(history.historyID) as play_count')
            )
            ->where('test.mode', 0) // <--- QUAN TRỌNG: Chỉ lấy bài Public
            ->groupBy('test.testID', 'test.title', 'test.dayCreated', 'test.quantity', 'test.mode', 'test.userID', 'test.timeEachQuestion', 'users.fullName');

        // Nếu có từ khóa tìm kiếm
        if ($keyword) {
            $query->where('test.title', 'like', '%' . $keyword . '%');
        }

        // Sắp xếp mới nhất và phân trang (9 bài mỗi trang)
        $tests = $query->orderBy('test.dayCreated', 'desc')->paginate(9);

        return view('layout.explore', compact('tests'));
    }

    // 2. HÀM XỬ LÝ NHẬP MÃ (Tìm cả bài Private lẫn Public)
    public function private(Request $request)
    {
        $inputID = $request->get('private_id');

        // Tìm bài test theo ID
        $test = DB::table('test')->where('testID', $inputID)->first();

        if ($test) {
            // Nếu tìm thấy -> Chuyển hướng đến trang Confirm (joinTest)
            return redirect()->route('joinTest', ['id' => $test->testID]);
        } else {
            // Nếu không thấy -> Báo lỗi
            return redirect()->back()->with('error', 'Không tìm thấy bài Test với ID: #' . $inputID);
        }
    }

    public function favourites()
    {
        $userID = Auth::id();

        // Join bảng favorites với bảng test và users
        $favorites = DB::table('favourites')
            ->join('test', 'favourites.testID', '=', 'test.testID')
            ->join('users', 'test.userID', '=', 'users.userID')
            ->where('favourites.userID', $userID)
            ->select(
                'test.*',
                'users.fullName as author_name',
                'favourites.created_at as favorited_at' // Ngày thả tim
            )
            ->orderBy('favourites.created_at', 'desc')
            ->get();

        return view('layout.favourite', compact('favorites'));
    }

    public function statistic()
    {
        $userID = Auth::id();

        // 1. THỐNG KÊ TỔNG QUAN (SUMMARY CARDS)
        $totalTestsDone = DB::table('history')->where('userID', $userID)->count();

        // Tính tổng số câu đúng và tổng số câu đã làm trong toàn bộ lịch sử
        $stats = DB::table('history')
            ->where('userID', $userID)
            ->select(
                DB::raw('SUM(correct_question) as total_correct'),
                DB::raw('SUM(question_completed) as total_attempted')
            )
            ->first();

        $totalCorrect = $stats->total_correct ?? 0;
        $totalAttempted = $stats->total_attempted ?? 0;
        // Tránh lỗi chia cho 0 nếu chưa làm bài nào
        $averageAccuracy = ($totalAttempted > 0) ? round(($totalCorrect / $totalAttempted) * 100, 1) : 0;

        $totalFavorites = DB::table('favourites')->where('userID', $userID)->count();


        // 2. DỮ LIỆU BIỂU ĐỒ ĐƯỜNG: Hoạt động 7 ngày gần đây
        // Mục tiêu: Lấy ngày và số bài làm trong ngày đó
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(6); // Lấy 7 ngày (hôm nay + 6 ngày trước)

        $activityData = DB::table('history')
            ->select(DB::raw('DATE(done_at) as date'), DB::raw('count(*) as count'))
            ->where('userID', $userID)
            ->whereBetween('done_at', [$startDate->format('Y-m-d 00:00:00'), $endDate->format('Y-m-d 23:59:59')])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Chuẩn bị dữ liệu cho Chart.js (Cần mảng cho nhãn ngày và mảng cho dữ liệu số)
        $chartLabels = [];
        $chartData = [];

        // Tạo vòng lặp 7 ngày để đảm bảo ngày nào không làm bài vẫn hiện số 0
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $displayDate = Carbon::now()->subDays($i)->format('d/m'); // Hiển thị dạng ngày/tháng

            // Tìm xem ngày này có trong kết quả DB không
            $dayData = $activityData->firstWhere('date', $date);

            $chartLabels[] = $displayDate;
            $chartData[] = $dayData ? $dayData->count : 0;
        }


        // 3. DỮ LIỆU BIỂU ĐỒ TRÒN (Accuracy Doughnut)
        // Đã tính ở trên: $totalCorrect và $totalAttempted
        $totalWrong = $totalAttempted - $totalCorrect;
        $accuracyChartData = [$totalCorrect, $totalWrong];


        // 4. LẤY VÀI BÀI LÀM GẦN NHẤT ĐỂ HIỂN THỊ BẢNG NHỎ
        $recentActivity = DB::table('history')
            ->join('test', 'history.testID', '=', 'test.testID')
            ->where('history.userID', $userID)
            ->select('test.title', 'history.correct_question', 'history.question_completed', 'history.done_at')
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
