<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Attendance;
use App\Models\AttendanceToken;
use App\Models\User;
use App\Models\Setting;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('participant.user');

        // FILTER NAMA
        if ($request->filled('nama')) {
            $query->whereHas('participant.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama . '%');
            });
        }

        // FILTER TANGGAL
        if ($request->filled('tanggal')) {
            $query->whereDate('date', $request->tanggal);
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest()->paginate(10)->withQueryString();

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
    public function setting()
    {
        $setting = Setting::first();
        return view('admin.setting', compact('setting'));
    }
    public function updateSetting(Request $request)
    {
        $request->validate([
            'office_lat'   => 'required|numeric',
            'office_lng'   => 'required|numeric',
            'radius_meter' => 'required|integer|min:10',
        ]);

        $setting = Setting::first();

        if ($setting) {
            $setting->update($request->only('office_lat', 'office_lng', 'radius_meter'));
        } else {
            Setting::create($request->only('office_lat', 'office_lng', 'radius_meter'));
        }

        return back()->with('success', 'Setting berhasil disimpan');
    }

    /**
     * List all users
     */
    public function users()
    {
        $users = User::with('participant')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show create user form
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store a new user
     */

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'user created successfully 😤');
    }

    /**
     * Show edit user form
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update a user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'npm' => 'nullable|string|max:255',
            'institusi' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        if ($validated['password']) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Update or create participant if role is user
        if ($validated['role'] === 'user') {
            $user->participant()->updateOrCreate(['user_id' => $user->id], [
                'npm' => $validated['npm'] ?? '0000000000',
                'institusi' => $validated['institusi'] ?? 'Universitas Lampung',
            ]);
        }

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully');
    }

    /**
     * Delete a user
     */
    public function deleteUser(User $user)
    {
        $user->delete();
        
        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully');
    }
}
