<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\DoTestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function create()
    {
        return view('test.create');
    }

    public function postCreate(CreateRequest $request)
    {

        $file = $request->file('vocabFile'); //lay file upload
        if ($request->hasFile('vocabFile')) {
            $path = $request->file('vocabFile')->store('vocab_files'); //luu file vao storage  
            session(['filePath' => $path]); //luu path vao session
        } else {
            $path = session('filePath'); //lay path trong session ra
        }
        $content = Storage::get($path);
        $row = explode("\n", $content);
        $row = array_filter($row, function ($line) { //xoa khoang trang
            return trim($line) !== '';
        });
        $vocabs = []; //tao mang luu tu vung
        foreach ($row as $r) {
            $part = explode(':', $r);
            if (count($part) == 2) {
                $vocabs[] = [
                    'word' => trim($part[0]),
                    'meaning' => trim($part[1])
                ];
            }
        }

        if (empty($vocabs)) {
            return back()->withErrors(['vocabFile' => 'File error !']);
        }

        $isMax = $request->has('all'); //bien lay tat ca tu vung
        $totalVocab = count($vocabs); //dem so luong tu
        shuffle($vocabs); //xao tron
        if ($isMax) { //kiem tra co chon max k
            $quantity = $totalVocab;
            $vocabToSave = $vocabs;
        } else {
            $quantity = min($request->get('quantity'), $totalVocab); //lay min giua so tu user nhap va tong tu
            $vocabToSave = array_slice($vocabs, 0, $quantity); //lay cac tu 0 den quantity
        }

        //luu vao 2 bang trong csdl
        $testID = DB::table('test')->insertGetId([
            'userID' => Auth::id(),
            'title' => $request->get('title'),
            'timeEachQuestion' => $request->get('timeEachQuestion'),
            'quantity' => $quantity,
            'mode' => $request->get('mode'),
            'dayCreated' => now()
        ]);

        $saveVocabToDB = []; //luu tu vung vao mang
        foreach ($vocabToSave as $v) {
            $saveVocabToDB[] = [
                'testID' => $testID,
                'question' => $v['word'],
                'meaning' => $v['meaning']
            ];
        }
        $vocab = DB::table('vocabulary')->insert($saveVocabToDB); //insert vao bang vocabulary
        $test = DB::table('test')->where('testID', $testID)->first();
        session(['testID' => $testID]);
        session(['vocab' => $vocab]);
        session(['vocabIndex' => 0]);
        session(['score' => 0]);
        session()->forget([
            'filePath',
            'saved_title',
            'saved_time',
            'saved_mode',
            'saved_quantity'
        ]);
        return view('test.confirmCreate', compact('test'));
    }

    public function doTest()
    {
        $testID = session('testID');
        $vocab = DB::table('vocabulary')->where('testID', $testID)->get();
        $vocabIndex = session('vocabIndex', 0);
        if ($vocabIndex >= $vocab->count()) {
            return $this->saveHistory($testID, true);
        }
        $test = DB::table('test')->where('testID', $testID)->first();
        return view('test.doTest', compact('vocab', 'test'));
    }

    public function postDoTest(DoTestRequest $request)
    {
        $vocabIndex = session('vocabIndex', 0);
        $testID = session('testID');
        $vocabs = DB::table('vocabulary')->where('testID', $testID)->get();

        // Check cơ bản
        if ($vocabIndex >= $vocabs->count()) {
            return $this->saveHistory($testID, true);
        }

        $answer = $request->get('answer') ?? '';
        $currentScore = session('score', 0); // Lấy điểm hiện tại

        // Logic Check Next
        if ($answer === '__next') {
            session(['vocabIndex' => $vocabIndex + 1]);
            return redirect()->route('doTest');
        }

        // Logic Check Đúng/Sai
        if (strtolower(trim($answer)) === strtolower(trim($vocabs[$vocabIndex]->meaning))) {
            session(['score' => $currentScore + 1]);

            return back()->with([
                'message' => 'Correct answer!',
                'status' => 'correct'
            ]);
        } else {
            return back()->with([
                'message' => 'Wrong answer! The answer is: ' . $vocabs[$vocabIndex]->meaning,
                'status' => 'wrong'
            ]);
        }
    }

    // 4. HÀM MỚI: XỬ LÝ NÚT THOÁT (Lưu dở dang)
    public function cancelTest()
    {
        $testID = session('testID');
        if ($testID) {
            return $this->saveHistory($testID, false); // false = chưa xong hẳn
        }
        return redirect()->route('create');
    }

    // 5. HÀM RIÊNG ĐỂ LƯU VÀO BẢNG HISTORY
    private function saveHistory($testID, $isFinished)
    {
        if (empty($testID)) {
            return redirect()->route('create')->with('error', 'Phiên làm việc đã hết hạn hoặc không tìm thấy bài thi.');
        }
        $userID = Auth::id();
        $score = session('score', 0);         // Số câu đúng
        $completed = session('vocabIndex', 0); // Số câu đã làm

        $lastAttempt = DB::table('history')
            ->where('testID', $testID)
            ->where('userID', $userID)
            ->max('numOfPlay'); // Hàm max sẽ lấy giá trị cao nhất

        // Nếu chưa chơi lần nào ($lastAttempt là null) thì gán là 1, ngược lại cộng thêm 1
        $currentAttempt = $lastAttempt ? ($lastAttempt + 1) : 1;

        // Insert vào bảng history của bạn
        DB::table('history')->insert([
            'testID' => $testID,
            'userID' => $userID,
            'correct_question' => $score,
            'question_completed' => $completed,
            'numOfPlay' => $currentAttempt,
            'done_at' => now()
            // Schema của bạn không có cột timestamps (created_at) nên không cần thêm
        ]);
        
        $attemptCount = DB::table('history')
            ->where('testID', $testID)
            ->where('userID', $userID)
            ->count();

        $totalQuestions = DB::table('vocabulary')->where('testID', $testID)->count();

        $isFavorited = DB::table('favourites')
            ->where('userID', Auth::id())
            ->where('testID', $testID)
            ->exists(); // Trả về true/false

        $resultData = [
            'testID' => $testID,
            'score' => $score,
            'completed' => $completed,
            'total_test' => $totalQuestions,
            'percentage' => ($completed > 0) ? round(($score / $completed) * 100) : 0,
            'attempt' => $currentAttempt,
            'isFavorited' => $isFavorited
        ];

        // Xóa session để reset
        session()->forget(['vocabIndex', 'testID', 'score']);

        return view('test.result', compact('resultData'));
    }

    // Thêm vào TestController.php

    public function retryTest($testID)
    {
        // 1. Kiểm tra bài test có tồn tại không
        $test = DB::table('test')->where('testID', $testID)->first();

        if (!$test) {
            return redirect()->route('create')->with('error', 'Bài test không tồn tại.');
        }

        
        session(['testID' => $testID]);
        session(['vocabIndex' => 0]); 
        session(['score' => 0]);      

        // 3. Chuyển hướng thẳng đến trang làm bài
        return redirect()->route('doTest');
    }

    public function joinTest($id)
    {
        // 1. Tìm bài test trong DB
        $test = DB::table('test')->where('testID', $id)->first();

        // Kiểm tra nếu bài test không tồn tại
        if (!$test) {
            return redirect()->route('home')->with('error', 'Bài thi không tồn tại!');
        }

        
        session(['testID' => $id]);     
        session(['vocabIndex' => 0]);   
        session(['score' => 0]);        

        session()->forget(['filePath', 'vocab']);
        return view('test.confirmCreate', compact('test'));
    }

    public function favourite($id)
    {
        $userID = Auth::id();

        // Kiểm tra xem đã like chưa
        $existing = DB::table('favourites')
            ->where('userID', $userID)
            ->where('testID', $id)
            ->first();

        if ($existing) {
            // Nếu có rồi -> XÓA (Un-like)
            DB::table('favourites')->where('id', $existing->id)->delete();
            $message = 'Đã xóa khỏi mục yêu thích!';
        } else {
            // Nếu chưa có -> THÊM (Like)
            DB::table('favourites')->insert([
                'userID' => $userID,
                'testID' => $id,
                'created_at' => now()
            ]);
            $message = 'Đã thêm vào mục yêu thích!';
        }

        return redirect()->back()->with('success', $message);
    }
}
