<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Attendance;
use App\Models\AttendanceToken;

class AdminController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('participant.user')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.dashboard', compact('attendances'));
    }

    public function qr()
    {
        $token = AttendanceToken::create([
            'token' => Str::random(10),
            'attendance_date' => now()->toDateString(),
        ]);

        return view('admin.qr', compact('token'));
    }
}
