<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::all();
        
        // Get an admin or manager user for the created_by field
        $creator = User::whereIn('role', ['admin', 'manager'])->first();
        if (!$creator) {
            // If no admin or manager, use the first user
            $creator = User::first();
        }

        // Create upcoming event
        $upcomingEvent = Event::create([
            'name' => 'Annual Championships',
            'description' => 'The annual championships for all teams',
            'location' => 'Main Stadium',
            'date' => Carbon::now()->addWeeks(2),
            'entry_fee' => 100.00,
            'created_by' => $creator->id,
        ]);

        // Attach teams to the upcoming event
        $upcomingEvent->teams()->attach($teams->pluck('id'));

        // Create ongoing event
        $ongoingEvent = Event::create([
            'name' => 'Seasonal Tournament',
            'description' => 'The seasonal tournament for qualifying teams',
            'location' => 'City Arena',
            'date' => Carbon::now()->addDays(3),
            'entry_fee' => 75.00,
            'created_by' => $creator->id,
        ]);

        // Attach first two teams to the ongoing event
        $ongoingEvent->teams()->attach($teams->take(2)->pluck('id'));

        // Create past event
        $pastEvent = Event::create([
            'name' => 'Friendly Match',
            'description' => 'A friendly match between teams',
            'location' => 'Local Stadium',
            'date' => Carbon::now()->subWeeks(1),
            'entry_fee' => 50.00,
            'created_by' => $creator->id,
        ]);

        // Attach the last team to the past event
        $pastEvent->teams()->attach($teams->last()->id);
    }
} 