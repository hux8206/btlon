<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequest;
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

        $row = file($file->getRealPath(),FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); //doc file , xoa khoang trang

        $vocabs = []; //tao mang luu tu vung
        foreach($row as $r)
        {
            $part = explode(':',$r);
            if(count($part) == 2)
            {
                $vocabs[] = [
                    'word' => trim($part[0]),
                    'meaning' => trim($part[1])
                ];
            }
        }

        if(empty($vocabs)){
            return back()->withErrors(['vocabFile'=>'File error !']);
        }

        $isMax = $request->has('all'); //bien lay tat ca tu vung
        $totalVocab = count($vocabs); //dem so luong tu
        if($isMax){ //kiem tra co chon max k
            $quantity = $totalVocab;
            $vocabToSave = $vocabs;
        }else{
            $quantity = min($request->get('quantity'), $totalVocab); //lay min giua so tu user nhap va tong tu
            shuffle($vocabs); //xao tron
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
        foreach($vocabToSave as $v)
        {
            $saveVocabToDB[] = [
                'testID' => $testID,
                'question' => $v['word'],
                'meaning' => $v['meaning']
            ];
        }
        DB::table('vocabulary')->insert($saveVocabToDB); //insert vao bang vocabulary

        $test = DB::table('test')->where('testID',$testID)->first();
        return view('test.confirmCreate',compact('test'));
    }

    public function dotest()
    {
        return view('test.doTest');
    }
}
