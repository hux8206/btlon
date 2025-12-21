<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['checklogin','admin'])->controller(AdminController::class)->group(function () {
    Route::get('home', 'admin')->name('admin');
    Route::patch('/user/{id}','status')->name('status');
    Route::get('tests','tests')->name('tests');
});
