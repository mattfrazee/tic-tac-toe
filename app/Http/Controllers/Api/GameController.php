<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Honor ?limit=... (clamped 1..100), default 50
        $limit = (int) $request->input('limit', 50);
        $limit = max(1, min($limit, 100));

        return Game::query()->latest()->withCount('moves')->take($limit)->get();
//        return Game::latest()->withCount('moves')->take($limit)->get();
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
    public function show(Game $game)
    {
        return response()->json($game->load('moves'));
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
    public function destroy(Game $game)
    {
        $game->delete();

        return response()->json($game);
    }

    public function clearScores()
    {
        if (app()->environment('production')) {
            return response()->json([
                'message' => 'Score clearing is disabled in production.',
            ], 403);
        }

        try {
            // Delete moves first due to foreign key constraints
            DB::table('moves')->truncate();
            DB::table('games')->truncate();

            return response()->json([
                'message' => 'All game scores and moves have been cleared successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to clear scores.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
