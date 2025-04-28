<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'location', 'date', 'entry_fee', 'created_by'];

    /**
     * Get the user that created the event.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the event teams for this event.
     */
    public function eventTeams()
    {
        return $this->hasMany(EventTeam::class);
    }

    /**
     * Get the teams participating in this event through the event_teams pivot table.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'event_teams');
    }
}
