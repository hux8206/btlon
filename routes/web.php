<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EditVocabController;

Route::controller(UserController::class)->group(function () {
    Route::get('/','start')->name('start');
    Route::get('register', 'register')->name('register');
    Route::post('register', 'postRegister')->name('postRegister');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'postLogin')->name('postLogin');
    Route::get('logout','logout')->name('logout');
    Route::get('profile','profile')->name('profile');
});

Route::prefix('create')->middleware('checklogin')->controller(TestController::class)->group(function(){
    Route::get('/','create')->name('create');
    Route::post('/confirm','postCreate')->name('postCreate');
    Route::get('/dotest','doTest')->name('doTest');
    Route::post('/dotest','postDoTest')->name('postDoTest');
    Route::get('/confirm','postCreate')->name('confirmCreate');
});

Route::prefix('vocabDetail')->middleware('checklogin')->controller(EditVocabController::class)->group(function(){
    Route::get('/','list')->name('list');
    Route::get('/add','add')->name('add');
    Route::post('/add','postAdd')->name('postAdd');
    Route::get('/edit/{index}','edit')->name('edit');
    Route::put('/edit/{index}','postEdit')->name('postEdit');
    Route::delete('/delete/{index}','delete')->name('delete');
    Route::post('/uploadFile','checkUpload')->name('checkUpload');
});

Route::middleware('checklogin')->get('home',[MainController::class,'home'])->name('home');