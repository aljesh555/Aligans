<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::create([
            'name' => 'Team Alpha',
            'region' => 'North',
            'level' => 'Professional',
            'coach_name' => 'John Smith',
        ]);

        Team::create([
            'name' => 'Team Beta',
            'region' => 'South',
            'level' => 'Semi-Professional',
            'coach_name' => 'Jane Doe',
        ]);

        Team::create([
            'name' => 'Team Reserve',
            'region' => 'East',
            'level' => 'Amateur',
            'coach_name' => 'Mike Johnson',
        ]);
    }
} 