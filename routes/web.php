<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Models\User;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $user = Auth::user();

    if ($user instanceof User && $user->isAdmin()) {
        return redirect('/dashboard'); // ✅ balik ke yang lama dulu
    }

    return redirect('/checkin');
});

Route::middleware('auth')->group(function () {

    // USER
    Route::get('/checkin', [AttendanceController::class, 'checkinForm'])->name('checkin');
    Route::post('/checkin', [AttendanceController::class, 'checkin']);
    Route::post('/checkout', [AttendanceController::class, 'checkout']);

    // ADMIN (KEEP EXISTING)
    Route::middleware('admin')->group(function () {

        // ✅ PAKAI YANG LAMA DULU
        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('dashboard');

        // 🔥 TAMBAHAN BARU (TIDAK MENGGANGGU)
        Route::get('/admin/attendance/{id}', [AdminAttendanceController::class, 'show'])
            ->name('admin.attendance.show');

        Route::get('/admin/export/excel', [AdminAttendanceController::class, 'exportExcel'])
            ->name('admin.export.excel');

        // EXISTING
        Route::get('/admin/qr', [AdminController::class, 'qr'])->name('qr');

        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::patch('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.destroy');
        Route::get('/admin/setting', [AdminController::class, 'setting'])->name('admin.setting');
        Route::post('/admin/setting', [AdminController::class, 'updateSetting'])->name('admin.setting.update');
    });

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';