<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->participant) {
        return redirect('/checkin');
    }

    return redirect('/dashboard');
});

Route::middleware('auth')->group(function () {

    Route::get('/checkin', [AttendanceController::class, 'checkinForm'])
        ->name('checkin');

    Route::post('/checkin', [AttendanceController::class, 'checkin']);

    Route::post('/checkout', [AttendanceController::class, 'checkout']);

    Route::get('/dashboard', [AdminController::class, 'index'])
        ->name('dashboard');

    Route::get('/admin/qr', [AdminController::class, 'qr'])
        ->name('qr');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';