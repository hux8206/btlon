<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::controller(UserController::class)->group(function () {
    Route::get('/','start')->name('start');
    Route::get('register', 'register')->name('register');
    Route::post('register', 'postRegister')->name('postRegister');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'postLogin')->name('postLogin');
    Route::get('logout','logout')->name('logout');
    Route::get('profile','profile')->name('profile');
});

Route::middleware('checklogin')->controller(TestController::class)->group(function(){
    Route::get('create','create')->name('create');
    Route::post('create','postCreate')->name('postCreate');
});

Route::middleware('checklogin')->get('home',[MainController::class,'home'])->name('home');