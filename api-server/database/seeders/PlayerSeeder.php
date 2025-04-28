<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::all();

        Player::create([
            'name' => 'John Doe',
            'position' => 'Forward',
            'team_id' => $teams[0]->id,
            'age' => 24,
        ]);

        Player::create([
            'name' => 'Jane Smith',
            'position' => 'Midfielder',
            'team_id' => $teams[0]->id,
            'age' => 22,
        ]);

        Player::create([
            'name' => 'Mike Johnson',
            'position' => 'Defender',
            'team_id' => $teams[1]->id,
            'age' => 26,
        ]);

        Player::create([
            'name' => 'Sarah Williams',
            'position' => 'Goalkeeper',
            'team_id' => $teams[1]->id,
            'age' => 25,
        ]);

        Player::create([
            'name' => 'Alex Brown',
            'position' => 'Forward',
            'team_id' => $teams[2]->id,
            'age' => 21,
        ]);
    }
} 