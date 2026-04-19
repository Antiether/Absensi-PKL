<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('participant.user');

        if ($request->nama) {
            $query->whereHas('participant.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama . '%');
            });
        }

        if ($request->tanggal) {
            $query->whereDate('date', $request->tanggal);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $query->orderBy('date', $request->sort ?? 'desc');

        $attendances = $query->paginate(10)->withQueryString();

        return view('admin.dashboard', compact('attendances'));
    }

    public function show($id)
    {
        $attendance = Attendance::with('participant.user')->findOrFail($id);

        return view('admin.attendance.show', compact('attendance'));
    }

    // 🔥 TANPA COMPOSER (CSV)
    public function exportExcel()
    {
        $attendances = Attendance::with('participant.user')->get();

        $filename = "attendance.csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($attendances) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Nama', 'Tanggal', 'Check-in', 'Check-out', 'Status']);

            foreach ($attendances as $a) {
                fputcsv($file, [
                    $a->participant->user->name,
                    $a->date,
                    $a->checkin_time,
                    $a->checkout_time,
                    $a->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}