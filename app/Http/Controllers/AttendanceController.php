<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\AttendanceToken;
use App\Models\Setting;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function checkinForm()
    {
        $setting = Setting::first();
        return view('checkin', compact('setting'));
    }

    // ================= CHECK-IN =================
    public function checkin(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:5120',
            'lat'   => 'required|numeric',
            'lng'   => 'required|numeric',
        ]);

        $token = trim($request->token) ?: trim($request->token_manual);

        if (!$token) {
            return back()->withErrors('Token QR wajib diisi');
        }

        $user = Auth::user();

        if (!$user->participant) {
            abort(403, 'User bukan peserta PKL');
        }

        $participant = $user->participant;
        $today = Carbon::today()->toDateString();

        $existing = Attendance::where('participant_id', $participant->id)
            ->where('date', $today)
            ->first();

        if ($existing) {
            return back()->withErrors('Sudah check-in hari ini');
        }

        $setting = Setting::first();

        $distance = $this->distance(
            $request->lat,
            $request->lng,
            $setting->office_lat,
            $setting->office_lng
        );

        if ($distance > $setting->radius_meter) {
            return back()->withErrors('Di luar radius');
        }

        $validToken = AttendanceToken::where('token', $token)
            ->where('attendance_date', $today)
            ->first();

        if (!$validToken) {
            return back()->withErrors('Token tidak valid');
        }

        // 🔥 FIX UPLOAD (CONSISTENT NAMING)
        $photo = $request->file('photo');
        $filename = time() . '_' . Auth::id() . '.jpg';
        $photo->storeAs('checkin', $filename, 'public');
        $photoPath = 'checkin/' . $filename;

        Attendance::create([
            'participant_id' => $participant->id,
            'date'           => $today,
            'checkin_time'   => now(),
            'checkin_lat'    => $request->lat,
            'checkin_lng'    => $request->lng,
            'checkin_photo'  => $photoPath,
            'status'         => 'hadir',
        ]);

        return back()->with('success', 'Check-in berhasil');
    }

    // ================= CHECK-OUT =================
    public function checkout(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
            'lat'   => 'required|numeric',
            'lng'   => 'required|numeric',
        ]);

        $token = trim($request->token) ?: trim($request->token_manual);

        if (!$token) {
            return back()->withErrors('Token QR wajib diisi');
        }

        $user = Auth::user();

        if (!$user->participant) {
            abort(403, 'User bukan peserta PKL');
        }

        $participant = $user->participant;
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('participant_id', $participant->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return back()->withErrors('Belum check-in');
        }

        if ($attendance->checkout_time) {
            return back()->withErrors('Sudah check-out');
        }

        $setting = Setting::first();

        $distance = $this->distance(
            $request->lat,
            $request->lng,
            $setting->office_lat,
            $setting->office_lng
        );

        if ($distance > $setting->radius_meter) {
            return back()->withErrors('Di luar radius');
        }

        $validToken = AttendanceToken::where('token', $token)
            ->where('attendance_date', $today)
            ->first();

        if (!$validToken) {
            return back()->withErrors('Token tidak valid');
        }

        // 🔥 FIX: beda folder + nama beda
        $photo = $request->file('photo');
        $filename = time() . '_' . Auth::id() . '_out.jpg';
        $photo->storeAs('checkout', $filename, 'public');
        $photoPath = 'checkout/' . $filename;

        $attendance->update([
            'checkout_time'  => now(),
            'checkout_lat'   => $request->lat,
            'checkout_lng'   => $request->lng,
            'checkout_photo' => $photoPath,
            'status'         => 'pulang',
        ]);

        return back()->with('success', 'Check-out berhasil');
    }

    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }
}