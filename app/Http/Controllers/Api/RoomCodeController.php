<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomCode;
use Illuminate\Http\Request;

class RoomCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = (int)$request->input('limit', 50);
        $limit = max(1, min($limit, 100));

        $room_codes = RoomCode::query()
            ->latest()
            ->take($limit)
            ->get();

        return response()->json($room_codes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $roomCode = RoomCode::create();

        return response()->json($roomCode);
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomCode $roomCode)
    {
        return response()->json($roomCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomCode $roomCode)
    {
        $roomCode->delete();

        return response()->json($roomCode);
    }

    /**
     * Remove specified resources from storage.
     */
    public function destroyExpiredRooms()
    {
        $roomCodes = RoomCode::wherePast('expires_on');
        $totalRoomCodes = $roomCodes->count();
        if ($totalRoomCodes) {
            $roomCodes->delete();
        }

        return response()->json([
            'success' => true,
            'roomCodesRemoved' => $totalRoomCodes
        ]);
    }
}
