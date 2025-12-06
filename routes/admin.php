<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['checklogin','admin'])->controller(AdminController::class)->group(function () {
    Route::get('admin', 'admin')->name('admin');
});
