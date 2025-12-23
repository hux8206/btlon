<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile()
    {
        $userID = Auth::id();
        $createdTests = DB::table('test')
            ->where('userID', $userID)
            ->orderBy('dayCreated', 'desc')
            ->limit(5)
            ->get();

        $playedTests = DB::table('history')
            ->join('test', 'history.testID', '=', 'test.testID')
            ->where('history.userID', $userID)
            ->select(
                'test.testID',
                'test.title',
                'test.quantity',
                'history.correct_question',
                'history.question_completed',
                'history.done_at' 
            )
            ->orderBy('history.done_at', 'desc') // Sắp xếp mới nhất lên đầu
            ->get();

        $totalPlayed = DB::table('history')->where('userID', $userID)->count();
        return view('profile.profile', compact('createdTests','playedTests','totalPlayed'));
    }

    public function myTests(Request $request)
    {
        $userID = Auth::id();
        $keyword = $request->get('keyword');

        // Lấy danh sách bài test của chính user này
        $query = DB::table('test')
            ->leftJoin('history', 'test.testID', '=', 'history.testID')
            ->select('test.*', DB::raw('COUNT(history.historyID) as play_count')) // Đếm lượt chơi
            ->where('test.userID', $userID)
            ->groupBy('test.testID', 'test.title', 'test.quantity', 'test.timeEachQuestion', 'test.dayCreated', 'test.mode', 'test.userID');

        // Xử lý tìm kiếm
        if ($keyword) {
            $query->where('test.title', 'like', '%' . $keyword . '%');
        }

        $myTests = $query->orderBy('test.dayCreated', 'desc')->paginate(10);

        return view('profile.myTests', compact('myTests'));
    }

    public function manageTest($id)
    {
        $userID = Auth::id();

        // 1. Kiểm tra quyền sở hữu (Chỉ chủ bài mới được vào)
        $test = DB::table('test')->where('testID', $id)->where('userID', $userID)->first();
        if (!$test) return redirect()->route('myTests')->with('error', 'Bạn không có quyền quản lý bài này.');

        // 2. Lấy danh sách người khác đã chơi bài này
        $playerHistory = DB::table('history')
            ->join('users', 'history.userID', '=', 'users.userID')
            ->where('history.testID', $id)
            ->select('users.fullName', 'history.correct_question', 'history.question_completed', 'history.done_at', 'history.numOfPlay')
            ->orderBy('history.done_at', 'desc')
            ->get();
        return view('profile.manage', compact('test', 'playerHistory'));
    }

    public function updateTest(Request $request, $id)
    {
        // Validate đơn giản
        $request->validate([
            'title' => 'required|max:255',
            'time' => 'required|integer|min:10',
            'mode' => 'required|boolean'
        ]);

        DB::table('test')->where('testID', $id)->where('userID', Auth::id())->update([
            'title' => $request->title,
            'timeEachQuestion' => $request->time,
            'mode' => $request->mode
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật thông tin bài thi.');
    }

    public function deleteTest($id)
    {
        // Xóa bài test của mình (Cần xóa ràng buộc khóa ngoại nếu có, ở đây làm đơn giản)
        // Lưu ý: Nếu DB có ràng buộc foreign key cascade thì chỉ cần xóa bảng test là xong.
        // Nếu không thì phải xóa tay bảng vocabulary và history trước.

        DB::table('test')->where('testID', $id)->where('userID', Auth::id())->delete();

        return redirect()->route('myTests')->with('success', 'Đã xóa bài thi thành công.');
    }

    public function editVocab($id)
    {
        $userID = Auth::id();

        // 1. Kiểm tra quyền sở hữu bài test
        $test = DB::table('test')->where('testID', $id)->where('userID', $userID)->first();

        if (!$test) {
            return redirect()->route('myQuizzes')->with('error', 'Bạn không có quyền chỉnh sửa bài này.');
        }

        // 2. Lấy danh sách từ vựng hiện có
        $vocabs = DB::table('vocabulary')->where('testID', $id)->orderBy('vocabID', 'desc')->get();

        return view('profile.editVocab', compact('test', 'vocabs'));
    }

    public function addVocab(Request $request, $id)
    {
        // Validate
        $request->validate([
            'question' => 'required|max:255',
            'meaning' => 'required|max:255',
        ]);

        // Insert
        DB::table('vocabulary')->insert([
            'testID' => $id,
            'question' => $request->question,
            'meaning' => $request->meaning,
            // 'image' => ... (Nếu sau này bạn muốn thêm ảnh)
        ]);

        // Cập nhật lại số lượng câu hỏi trong bảng Test
        $count = DB::table('vocabulary')->where('testID', $id)->count();
        DB::table('test')->where('testID', $id)->update(['quantity' => $count]);

        return redirect()->back()->with('success', 'Đã thêm từ mới.');
    }

    public function updateVocab(Request $request, $vocabID)
    {
        // Cập nhật dòng từ vựng đó
        DB::table('vocabulary')->where('vocabID', $vocabID)->update([
            'question' => $request->question,
            'meaning' => $request->meaning
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật từ vựng.');
    }

    public function deleteVocab($vocabID)
    {
        // Lấy testID trước khi xóa để cập nhật lại số lượng
        $vocab = DB::table('vocabulary')->where('vocabID', $vocabID)->first();

        if ($vocab) {
            DB::table('vocabulary')->where('vocabID', $vocabID)->delete();

            // Cập nhật lại số lượng câu hỏi
            $count = DB::table('vocabulary')->where('testID', $vocab->testID)->count();
            DB::table('test')->where('testID', $vocab->testID)->update(['quantity' => $count]);
        }

        return redirect()->back()->with('success', 'Đã xóa từ vựng.');
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        // Validate dữ liệu
        $request->validate([
            'fullName' => 'required|string|max:255',
            // Email phải là duy nhất, trừ email của chính user này
            'email' => 'required|email|unique:users,email,' . $user->userID . ',userID',
        ]);

        // Cập nhật vào DB
        DB::table('users')->where('userID', $user->userID)->update([
            'fullName' => $request->fullName,
            'email' => $request->email
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật thông tin thành công!')->with('active_tab', 'settings');;
    }

    // 2. ĐỔI MẬT KHẨU
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', // confirmed check khớp với field new_password_confirmation
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu cũ có đúng không
        if (!Hash::check($request->current_password, $user->pass)) {
            return redirect()->back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }

        // Cập nhật mật khẩu mới (Mã hóa Hash)
        DB::table('users')->where('userID', $user->userID)->update([
            'pass' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công! Hãy đăng nhập lại nếu cần.')->with('active_tab', 'settings');;
    }
}
