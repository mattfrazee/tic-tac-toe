<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Game::latest()->withCount('moves')->take(50)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $game = Game::create($request->only([
            'player_x_name', 'player_o_name', 'first_player', 'winner', 'board_size'
        ]));

        foreach ($request->input('moves', []) as $i => $move) {
            $game->moves()->create([
                'mark' => $move['mark'],
                'row' => $move['row'],
                'col' => $move['col'],
                'turn' => $i + 1,
            ]);
        }

        return response()->json($game->load('moves'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
