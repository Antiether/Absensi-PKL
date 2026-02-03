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
    /**
     * Tampilkan form check-in / check-out
     */
    public function checkinForm()
    {
        $setting = Setting::first(); // lokasi kantor
        return view('checkin', compact('setting'));
    }

    /**
     * Proses CHECK-IN
     */
    public function checkin(Request $request)
    {
        // ================= VALIDASI INPUT =================
        $request->validate([
            'photo' => 'required|image|max:2048',
            'lat'   => 'required',
            'lng'   => 'required',
            'token' => 'required',
        ]);

        $user = Auth::user();

        // Pastikan user adalah peserta PKL
        if (!$user->participant) {
            abort(403, 'User bukan peserta PKL');
        }

        $participant = $user->participant;
        $today = Carbon::today()->toDateString();

        // ================= CEGAH DOUBLE CHECK-IN =================
        $existing = Attendance::where('participant_id', $participant->id)
            ->where('date', $today)
            ->first();

        if ($existing) {
            return back()->withErrors('Anda sudah check-in hari ini');
        }

        // ================= AMBIL SETTING LOKASI =================
        $setting = Setting::first();
        if (!$setting) {
            abort(500, 'Setting lokasi kantor belum diatur');
        }

        // ================= VALIDASI JARAK =================
        $distance = $this->distance(
            $request->lat,
            $request->lng,
            $setting->office_lat,
            $setting->office_lng
        );

        if ($distance > $setting->radius_meter) {
            return back()->withErrors('Lokasi di luar radius kantor');
        }

        // ================= VALIDASI QR TOKEN =================
        $validToken = AttendanceToken::where('token', $request->token)
            ->where('attendance_date', $today)
            ->first();

        if (!$validToken) {
            return back()->withErrors('QR tidak valid atau sudah kadaluarsa');
        }

        // ================= SIMPAN FOTO =================
        $photoPath = $request->file('photo')->store('checkin', 'public');

        // ================= SIMPAN ABSENSI =================
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

    /**
     * Proses CHECK-OUT
     */
    public function checkout(Request $request)
    {
        // ================= VALIDASI INPUT =================
        $request->validate([
            'photo' => 'required|image|max:2048',
            'lat'   => 'required',
            'lng'   => 'required',
            'token' => 'required',
        ]);

        $user = Auth::user();

        if (!$user->participant) {
            abort(403, 'User bukan peserta PKL');
        }

        $participant = $user->participant;
        $today = Carbon::today()->toDateString();

        // ================= AMBIL DATA ABSENSI =================
        $attendance = Attendance::where('participant_id', $participant->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return back()->withErrors('Belum check-in hari ini');
        }

        if ($attendance->checkout_time) {
            return back()->withErrors('Anda sudah check-out');
        }

        // ================= AMBIL SETTING =================
        $setting = Setting::first();
        if (!$setting) {
            abort(500, 'Setting lokasi kantor belum diatur');
        }

        // ================= VALIDASI JARAK =================
        $distance = $this->distance(
            $request->lat,
            $request->lng,
            $setting->office_lat,
            $setting->office_lng
        );

        if ($distance > $setting->radius_meter) {
            return back()->withErrors('Lokasi di luar radius kantor');
        }

        // ================= VALIDASI QR TOKEN =================
        $validToken = AttendanceToken::where('token', $request->token)
            ->where('attendance_date', $today)
            ->first();

        if (!$validToken) {
            return back()->withErrors('QR tidak valid atau sudah kadaluarsa');
        }

        // ================= SIMPAN FOTO =================
        $photoPath = $request->file('photo')->store('checkout', 'public');

        // ================= UPDATE ABSENSI =================
        $attendance->update([
            'checkout_time'  => now(),
            'checkout_lat'   => $request->lat,
            'checkout_lng'   => $request->lng,
            'checkout_photo' => $photoPath,
            'status'         => 'pulang',
        ]);

        return back()->with('success', 'Check-out berhasil');
    }

    /**
     * Hitung jarak GPS (Haversine Formula)
     */
    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}