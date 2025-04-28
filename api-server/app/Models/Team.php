<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'region', 'level', 'coach_name'];

    /**
     * Get the event teams for this team.
     */
    public function eventTeams()
    {
        return $this->hasMany(EventTeam::class);
    }

    /**
     * Get the events that this team is participating in through the event_teams pivot table.
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_teams');
    }

    /**
     * Get the players for this team.
     */
    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
