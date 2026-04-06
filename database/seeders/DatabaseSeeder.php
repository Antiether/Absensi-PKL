<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Participant;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Regular User (Participant)
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        Participant::create([
            'user_id' => $user->id,
            'npm' => '2357051012',
            'institusi' => 'Universitas Lampung',
        ]);

        $setting = new Setting;
        $setting->office_lat = -5.4290;
        $setting->office_lng = 105.2520;
        $setting->radius_meter = 100;
        $setting->save();
    }
}
