<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all staff users
        $users = User::where('role', 'staff')->get();
        
        if ($users->isEmpty()) {
            // If no staff users, get all users with customer role
            $users = User::where('role', 'customer')->get();
        }
        
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $twoDaysAgo = Carbon::today()->subDays(2);

        // Create attendance records for today
        foreach ($users as $index => $user) {
            if ($index % 4 == 0) {
                // Every 4th user is absent
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'status' => 'absent',
                ]);
            } else {
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'status' => 'present',
                ]);
            }
        }

        // Create attendance records for yesterday
        foreach ($users as $index => $user) {
            if ($index % 3 == 0) {
                // Every 3rd user is absent
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $yesterday,
                    'status' => 'absent',
                ]);
            } else {
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $yesterday,
                    'status' => 'present',
                ]);
            }
        }

        // Create attendance records for two days ago
        foreach ($users as $user) {
            Attendance::create([
                'user_id' => $user->id,
                'date' => $twoDaysAgo,
                'status' => 'present',
            ]);
        }
    }
} 