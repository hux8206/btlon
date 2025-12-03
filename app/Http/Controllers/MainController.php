<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home()
    {
        return view('layout.home');    
    }

    public function statistic()
    {
        return view('layout.statistic');
    }

    public function create ()
    {
        return view('layout.create');
    }
}
