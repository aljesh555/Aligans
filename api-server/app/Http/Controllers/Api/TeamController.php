<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::all();
        return response()->json(['data' => $teams]);
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
            'region' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'coach_name' => 'required|string|max:255',
        ]);

        $team = Team::create($validatedData);

        return response()->json([
            'message' => 'Team created successfully',
            'data' => $team
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
        $team = Team::with(['players', 'events'])->findOrFail($id);
        return response()->json(['data' => $team]);
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
        $team = Team::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'region' => 'sometimes|required|string|max:255',
            'level' => 'sometimes|required|string|max:255',
            'coach_name' => 'sometimes|required|string|max:255',
        ]);

        $team->update($validatedData);

        return response()->json([
            'message' => 'Team updated successfully',
            'data' => $team
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
        $team = Team::findOrFail($id);
        
        // Check if team has players or events
        if ($team->players()->count() > 0 || $team->events()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete team with associated players or events'
            ], 422);
        }
        
        $team->delete();

        return response()->json([
            'message' => 'Team deleted successfully'
        ]);
    }
}
