<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Attendance;
use App\Models\AttendanceToken;
use App\Models\User;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
