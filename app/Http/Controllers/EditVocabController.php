<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use App\Http\Requests\AddVocabRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EditVocabController extends Controller
{
    // Trong EditVocabController.php
    public function list()
    {
        $path = session('filePath');
        if (!$path) {
            return redirect()->back()->with('message', 'no found file');
        }
        $content = Storage::get($path);
        $rows = explode("\n", $content);
        $rows = array_filter($rows, function ($value) {
            return !empty(trim($value));
        });
        return view('test.vocabDetail.list', compact('rows'));
    }

    public function uploadAndEdit(Request $request)
    {
        // 1. Validate xem có file không
        $request->validate([
            'vocabFile' => 'required|file|mimes:csv,txt'
        ]);

        // 2. Lưu file và lưu đường dẫn vào Session
        if ($request->hasFile('vocabFile')) {
            $path = $request->file('vocabFile')->store('vocab_files');
            session(['filePath' => $path]); // Lưu path vào session

            // 3. Chuyển hướng sang trang List
            return redirect()->route('list');
        }

        return redirect()->back()->with('message', 'Vui lòng chọn file trước!');
    }

    public function add()
    {
        return view('test.vocabDetail.add');
    }

    public function postAdd(AddVocabRequest $request)
    {
        $word = trim($request->get('word'));
        $meaning = trim($request->get('meaning'));
        $path = session('filePath');
        $content = Storage::get($path);
        $rows = explode("\n", $content);
        $newRow = $word . ':' . $meaning;
        $rows[] = $newRow;
        Storage::put($path, implode("\n", $rows));
        return redirect()->route('list')->with('message', 'Add vocabulary success !');
    }

    public function edit($index)
    {
        $path = session('filePath');
        $content = Storage::get($path);
        $rows = explode("\n", $content);
        $row = $rows[$index];
        $path = explode(':', $row);
        $word = trim($path[0]);
        $meaning = trim($path[1]);
        return view('test.vocabDetail.edit', compact('word', 'meaning', 'index'));
    }

    public function postEdit(AddVocabRequest $request, $index) //cung 1 request coi add vi ca 2 validate giong nhau
    {
        $path = session('filePath');
        $content = Storage::get($path);
        $rows = explode("\n", $content);
        $newRow = trim($request->get('word')) . ':' . trim($request->get('meaning'));
        $rows[$index] = $newRow;
        Storage::put($path, implode("\n", $rows));
        return redirect()->route('list')->with('message', 'Edit vocabulary success !');
    }

    public function delete($index)
    {
        $path = session('filePath');
        $content = Storage::get($path);
        $rows = explode("\n", $content);
        if (isset($rows[$index])) {
            unset($rows[$index]);
        }
        $rows = array_values($rows);
        Storage::put($path, implode("\n", $rows));
        return redirect()->route('list')->with('message', 'delete vocabulary success !');
    }
}
