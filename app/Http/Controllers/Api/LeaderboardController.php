<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlayerStat;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int)$request->input('limit', 50);
        $limit = max(1, min($limit, 100));

        return PlayerStat::top($limit)->get();
    }

    public function topThreeLeaders()
    {
        return PlayerStat::top(3)->get();
    }
}
