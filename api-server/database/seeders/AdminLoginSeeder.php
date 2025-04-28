<?php

namespace Database\Seeders;

use App\Models\AdminLogin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin login
        AdminLogin::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_active' => true
        ]);
    }
} 