<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::with('teams')->get();
        return response()->json(['data' => $events]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'entry_fee' => 'required|numeric|min:0',
            'team_ids' => 'nullable|array',
            'team_ids.*' => 'exists:teams,id',
        ]);

        $userId = $request->user()->id;
        
        $event = Event::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'date' => $validatedData['date'],
            'entry_fee' => $validatedData['entry_fee'],
            'created_by' => $userId,
        ]);
        
        // Attach teams if provided
        if (isset($validatedData['team_ids'])) {
            $event->teams()->attach($validatedData['team_ids']);
        }

        return response()->json([
            'message' => 'Event created successfully',
            'data' => $event->load('teams')
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::with(['teams.players', 'creator'])->findOrFail($id);
        return response()->json(['data' => $event]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'entry_fee' => 'sometimes|required|numeric|min:0',
            'team_ids' => 'nullable|array',
            'team_ids.*' => 'exists:teams,id',
        ]);

        $event->update(collect($validatedData)->except('team_ids')->toArray());
        
        // Sync teams if provided
        if (isset($validatedData['team_ids'])) {
            $event->teams()->sync($validatedData['team_ids']);
        }

        return response()->json([
            'message' => 'Event updated successfully',
            'data' => $event->load('teams')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        
        // First detach all teams
        $event->teams()->detach();
        
        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully'
        ]);
    }
}
