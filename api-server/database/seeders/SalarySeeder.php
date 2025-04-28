<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Salary;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all staff users
        $users = User::whereIn('role', ['staff', 'manager'])->get();
        
        if ($users->isEmpty()) {
            // If no staff users, get admin users
            $users = User::where('role', 'admin')->get();
        }
        
        $currentMonth = Carbon::now()->format('Y-m');
        $lastMonth = Carbon::now()->subMonth()->format('Y-m');
        $twoMonthsAgo = Carbon::now()->subMonths(2)->format('Y-m');

        // Salary ranges based on role
        $salaryRanges = [
            'admin' => [8000, 12000],
            'manager' => [6000, 9000],
            'staff' => [3000, 5000],
            'customer' => [0, 0], // Customers don't receive salary
        ];

        // Create salary records for the current month
        foreach ($users as $user) {
            $role = $user->role;
            $range = $salaryRanges[$role] ?? $salaryRanges['staff'];
            
            Salary::create([
                'user_id' => $user->id,
                'amount' => rand($range[0], $range[1]),
                'month' => $currentMonth,
                'paid' => false, // Not paid yet for current month
            ]);
        }

        // Create salary records for last month
        foreach ($users as $user) {
            $role = $user->role;
            $range = $salaryRanges[$role] ?? $salaryRanges['staff'];
            
            Salary::create([
                'user_id' => $user->id,
                'amount' => rand($range[0], $range[1]),
                'month' => $lastMonth,
                'paid' => true, // Paid for last month
            ]);
        }

        // Create salary records for two months ago
        foreach ($users as $user) {
            $role = $user->role;
            $range = $salaryRanges[$role] ?? $salaryRanges['staff'];
            
            Salary::create([
                'user_id' => $user->id,
                'amount' => rand($range[0], $range[1]),
                'month' => $twoMonthsAgo,
                'paid' => true, // Paid for two months ago
            ]);
        }
    }
} 