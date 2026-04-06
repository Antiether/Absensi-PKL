<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Models\User;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $user = Auth::user();

    if ($user instanceof User && $user->isAdmin()) {
        return redirect('/dashboard');
    }

    return redirect('/checkin');
});

Route::middleware('auth')->group(function () {

    Route::get('/checkin', [AttendanceController::class, 'checkinForm'])
        ->name('checkin');

    Route::post('/checkin', [AttendanceController::class, 'checkin']);

    Route::post('/checkout', [AttendanceController::class, 'checkout']);

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('dashboard');

        Route::get('/admin/qr', [AdminController::class, 'qr'])
            ->name('qr');

        Route::get('/admin/users', [AdminController::class, 'users'])
            ->name('admin.users');

        Route::get('/admin/users/create', [AdminController::class, 'createUser'])
            ->name('admin.users.create');

        Route::post('/admin/users', [AdminController::class, 'storeUser'])
            ->name('admin.users.store');

        Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])
            ->name('admin.users.edit');

        Route::patch('/admin/users/{user}', [AdminController::class, 'updateUser'])
            ->name('admin.users.update');

        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])
            ->name('admin.users.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';