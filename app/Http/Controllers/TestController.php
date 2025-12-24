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
        if ($request->hasFile('vocabFile')) {
            $path = $request->file('vocabFile')->store('vocab_files');
            session(['filePath' => $path]);
        } else {
            $path = session('filePath');
        }

        $content = Storage::get($path);
        $row = explode("\n", $content);
        $row = array_filter($row, function ($line) {
            return trim($line) !== '';
        });

        $vocabs = [];
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

        $isMax = $request->has('all');
        $totalVocab = count($vocabs);
        shuffle($vocabs);

        if ($isMax) {
            $quantity = $totalVocab;
            $vocabToSave = $vocabs;
        } else {
            $quantity = min($request->get('quantity'), $totalVocab);
            $vocabToSave = array_slice($vocabs, 0, $quantity);
        }

        $testID = DB::table('test')->insertGetId([
            'userID' => Auth::id(),
            'title' => $request->get('title'),
            'timeEachQuestion' => $request->get('timeEachQuestion'),
            'quantity' => $quantity,
            'mode' => $request->get('mode'),
            'dayCreated' => now()
        ]);

        $saveVocabToDB = [];
        foreach ($vocabToSave as $v) {
            $saveVocabToDB[] = [
                'testID' => $testID,
                'question' => $v['word'],
                'meaning' => $v['meaning']
            ];
        }

        DB::table('vocabulary')->insert($saveVocabToDB);

        $test = DB::table('test')->where('testID', $testID)->first();

        session(['testID' => $testID]);
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

        if ($vocabIndex >= $vocabs->count()) {
            return $this->saveHistory($testID, true);
        }

        $answer = $request->get('answer') ?? '';
        $currentScore = session('score', 0);

        if ($answer === '__next') {
            session(['vocabIndex' => $vocabIndex + 1]);
            return redirect()->route('doTest');
        }

        if (strtolower(trim($answer)) === strtolower(trim($vocabs[$vocabIndex]->meaning))) {
            session(['score' => $currentScore + 1]);

            return back()->with([
                'message' => 'Correct answer!',
                'status' => 'correct'
            ]);
        }

        return back()->with([
            'message' => 'Wrong answer! The answer is: ' . $vocabs[$vocabIndex]->meaning,
            'status' => 'wrong'
        ]);
    }

    public function cancelTest()
    {
        $testID = session('testID');

        if ($testID) {
            return $this->saveHistory($testID, false);
        }

        return redirect()->route('create');
    }

    private function saveHistory($testID, $isFinished)
    {
        if (empty($testID)) {
            return redirect()->route('create')->with('error', 'Phiên làm việc đã hết hạn hoặc không tìm thấy bài thi.');
        }

        $userID = Auth::id();
        $score = session('score', 0);
        $completed = session('vocabIndex', 0);

        $lastAttempt = DB::table('history')
            ->where('testID', $testID)
            ->where('userID', $userID)
            ->max('numOfPlay');

        $currentAttempt = $lastAttempt ? ($lastAttempt + 1) : 1;

        DB::table('history')->insert([
            'testID' => $testID,
            'userID' => $userID,
            'correct_question' => $score,
            'question_completed' => $completed,
            'numOfPlay' => $currentAttempt,
            'done_at' => now()
        ]);

        $totalQuestions = DB::table('vocabulary')->where('testID', $testID)->count();

        $isFavorited = DB::table('favourites')
            ->where('userID', $userID)
            ->where('testID', $testID)
            ->exists();

        $resultData = [
            'testID' => $testID,
            'score' => $score,
            'completed' => $completed,
            'total_test' => $totalQuestions,
            'percentage' => ($completed > 0) ? round(($score / $completed) * 100) : 0,
            'attempt' => $currentAttempt,
            'isFavorited' => $isFavorited
        ];

        session()->forget(['vocabIndex', 'testID', 'score']);

        return view('test.result', compact('resultData'));
    }

    public function retryTest($testID)
    {
        $test = DB::table('test')->where('testID', $testID)->first();

        if (!$test) {
            return redirect()->route('create')->with('error', 'Bài test không tồn tại.');
        }

        session(['testID' => $testID]);
        session(['vocabIndex' => 0]);
        session(['score' => 0]);

        return redirect()->route('doTest');
    }

    public function joinTest($id)
    {
        $test = DB::table('test')->where('testID', $id)->first();

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

        $existing = DB::table('favourites')
            ->where('userID', $userID)
            ->where('testID', $id)
            ->first();

        if ($existing) {
            DB::table('favourites')->where('id', $existing->id)->delete();
            $message = 'Đã xóa khỏi mục yêu thích!';
        } else {
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
