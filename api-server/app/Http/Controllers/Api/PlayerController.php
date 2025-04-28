<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = Player::with('team')->get();
        return response()->json(['data' => $players]);
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
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:100',
        ]);

        $player = Player::create($validatedData);

        return response()->json([
            'message' => 'Player created successfully',
            'data' => $player
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
        $player = Player::with('team')->findOrFail($id);
        return response()->json(['data' => $player]);
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
        $player = Player::findOrFail($id);

        $validatedData = $request->validate([
            'team_id' => 'sometimes|required|exists:teams,id',
            'name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|string|max:255',
            'age' => 'sometimes|required|integer|min:1|max:100',
        ]);

        $player->update($validatedData);

        return response()->json([
            'message' => 'Player updated successfully',
            'data' => $player
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
        $player = Player::findOrFail($id);
        $player->delete();

        return response()->json([
            'message' => 'Player deleted successfully'
        ]);
    }
    
    /**
     * Get players by team.
     *
     * @param  int  $teamId
     * @return \Illuminate\Http\Response
     */
    public function getByTeam($teamId)
    {
        $team = Team::findOrFail($teamId);
        $players = $team->players;
        
        return response()->json(['data' => $players]);
    }
}
