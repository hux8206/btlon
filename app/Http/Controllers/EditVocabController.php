<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use App\Http\Requests\AddVocabRequest;
use App\Http\Requests\CreateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EditVocabController extends Controller
{
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

    public function checkUpload(Request $request)
    {
        // 1. Validate everything at once
        // If this fails, Laravel redirects back automatically with errors.
        // Inputs are automatically flashed to session (accessible via old('title')).
        $validated = $request->validate([
            'vocabFile' => 'required|file|mimes:csv,txt',
            'title'     => 'nullable|string', // Add validation for other fields
            'time'      => 'nullable|numeric',
            'mode'      => 'nullable',
            'quantity'  => 'nullable|numeric',
        ], [
            // Custom error message for the file
            'vocabFile.required' => 'Vui lòng chọn file trước!',
            'vocabFile.mimes'    => 'File phải có định dạng .csv hoặc .txt',
        ]);

        // 2. Handle the File Upload
        if ($request->hasFile('vocabFile')) {
            $path = $request->file('vocabFile')->store('vocab_files');

            // 3. Save ALL necessary data to session for the next step
            session([
                'filePath'       => $path,
                'saved_title'    => $request->input('title'),
                'saved_time'     => $request->input('timeEachQuestion'),
                'saved_mode'     => $request->input('mode'),
                'saved_quantity' => $request->input('quantity'),
            ]);

            return redirect()->route('list');
        }

        // This part is technically redundant because of the 'required' rule above,
        // but acts as a fallback if validation logic changes.
        return redirect()->back()->with('message', 'Upload thất bại.');
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
