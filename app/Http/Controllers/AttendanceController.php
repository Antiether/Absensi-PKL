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
        $setting = Setting::first();
        return view('checkin', compact('setting'));
    }

    /**
     * Proses CHECK-IN
     */
    public function checkin(Request $request)
    {
        // ================= VALIDASI INPUT =================
        $request->validate([
            'photo' => 'required|image|max:5120',
            'lat'   => 'required|numeric',
            'lng'   => 'required|numeric',
        ]);

        // ================= TOKEN (SCAN ATAU MANUAL) =================
        $token = trim($request->token) ?: trim($request->token_manual);

        if (!$token) {
            return back()->withErrors('Token QR wajib diisi (scan atau manual)');
        }

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
            return back()->withErrors(
                sprintf('Lokasi di luar radius kantor. Jarak Anda: %.2f meter (maksimal %d meter)', 
                    $distance, 
                    $setting->radius_meter
                )
            );
        }

        // ================= VALIDASI QR TOKEN =================
        $validToken = AttendanceToken::where('token', $token)
            ->where('attendance_date', $today)
            ->first();

        if (!$validToken) {
            return back()->withErrors('Token tidak valid atau sudah kadaluarsa untuk hari ini');
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
            'note'           => $request->note,
            'status'         => 'hadir',
        ]);

        return back()->with('success', 'Check-in berhasil pada ' . now()->format('H:i:s'));
    }

    /**
     * Proses CHECK-OUT
     */
    public function checkout(Request $request)
    {
        // ================= VALIDASI INPUT =================
        $request->validate([
            'photo' => 'required|image|max:2048',
            'lat'   => 'required|numeric',
            'lng'   => 'required|numeric',
        ]);

        // ================= TOKEN (SCAN ATAU MANUAL) =================
        $token = trim($request->token) ?: trim($request->token_manual);

        if (!$token) {
            return back()->withErrors('Token QR wajib diisi (scan atau manual)');
        }

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
            return back()->withErrors('Belum check-in hari ini. Silakan check-in terlebih dahulu');
        }

        if ($attendance->checkout_time) {
            return back()->withErrors('Anda sudah check-out pada ' . Carbon::parse($attendance->checkout_time)->format('H:i:s'));
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
            return back()->withErrors(
                sprintf('Lokasi di luar radius kantor. Jarak Anda: %.2f meter (maksimal %d meter)', 
                    $distance, 
                    $setting->radius_meter
                )
            );
        }

        // ================= VALIDASI QR TOKEN =================
        $validToken = AttendanceToken::where('token', $token)
            ->where('attendance_date', $today)
            ->first();

        if (!$validToken) {
            return back()->withErrors('Token tidak valid atau sudah kadaluarsa untuk hari ini');
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

        return back()->with('success', 'Check-out berhasil pada ' . now()->format('H:i:s'));
    }

    /**
     * Hitung jarak GPS menggunakan Haversine Formula
     * 
     * @param float $lat1 Latitude titik pertama
     * @param float $lon1 Longitude titik pertama
     * @param float $lat2 Latitude titik kedua
     * @param float $lon2 Longitude titik kedua
     * @return float Jarak dalam meter
     */
    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }
}