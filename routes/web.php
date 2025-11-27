<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});
Route::controller(UserController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'postRegister')->name('postRegister');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'postLogin')->name('postLogin');
});
Route::get('/home', function () {
    return view('layout.home');
});
