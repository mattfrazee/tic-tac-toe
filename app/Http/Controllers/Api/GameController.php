<?php

namespace App\Http\Controllers\Api;

use App\Enums\WinnerResult;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\PlayerStat;
use App\Models\RoomCode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = (int)$request->input('limit', 50);
        $limit = max(1, min($limit, 100));

        return Game::query()
            ->latest()
            ->with('moves')
            ->withCount('moves')
            ->whereIsOnline(false)
            ->take($limit)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $game = Game::create($request->only([
            'player_x_name',
            'player_o_name',
            'first_player',
            'winner',
            'board_size',
            'vs_computer',
            'room_code_id',
            'is_online',
        ]));

        foreach ($request->input('moves', []) as $i => $move) {
            $game->moves()->create([
                'mark' => $move['mark'],
                'row' => $move['row'],
                'col' => $move['col'],
                'turn' => $i + 1,
                'is_computer' => $move['is_computer'] ?? false,
            ]);
        }

        $this->updatePlayerStats($game);

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

    /*public function create(Request $request)
    {
        $game = Game::where('join_code', $request->join_code)->firstOrFail();
        $game->update(['player_o_name' => $request->player_o_name]);
        return response()->json($game);
    }*/

    public function join(Request $request)
    {
        try {
            $room = RoomCode::whereCode($request->get('roomCode'))->get()->first();
            if (! $room) {
                throw new Exception('No room exists using code: ' . $request->get('roomCode'));
            }
            $game = Game::where('room_code_id', $room->room_code_id)->get()->first();
            if ($game) {
                $game->update([
                    'player_o_name' => $request->get('playerO'),
                ]);
            } else {
                return response()->json(['error' => "Game doesn't exist."]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json([$room, $game]);
    }


    public function sync(Game $game)
    {
        return $game->load('moves');
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
            DB::table('player_stats')->truncate();

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

    public function lastHumanGame(): ?Game
    {
        return Game::whereVsComputer(false)->latest()->first();
    }

    public function lastComputerGame()
    {
        return response()->json(
            Game::whereVsComputer(true)->latest()->first()
        );
    }

    private function updatePlayerStats(Game $game)
    {
        $game->refresh();

        if (!$game->winner) {
            return;
        }

        $players = [
            'X' => $game->player_x_name,
            'O' => $game->player_o_name,
        ];

        foreach ($players as $playerMark => $playerName) {
            $isComputer = $game->moves()
                ->whereMark($playerMark)
                ->whereIsComputer(true)
                ->exists();

            if ($isComputer) {
                continue;
            }

            $stats = PlayerStat::firstOrCreate([
                'name' => $playerName
            ]);
            $stats->increment('games_played');
            if ($game->winner !== WinnerResult::DRAW) {
                if ($game->winner->value === $playerMark) {
                    $stats->increment('games_won');
                } else {
                    $stats->increment('games_lost');
                }
            }
        }

    }
}
