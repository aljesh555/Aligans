<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTeam extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'team_id'];

    /**
     * Get the event that owns the event team.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the team that owns the event team.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
