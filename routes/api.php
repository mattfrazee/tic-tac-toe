<?php

use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\RoomCodeController;

// Player Select
//Route::get('/player-select', [PlayerSelectController::class, 'index']);

// Game Play
Route::get('/games/last-human-game', [GameController::class, 'lastHumanGame']);
Route::delete('/games/clear', [GameController::class, 'clearScores']);
Route::post('/games/join', [GameController::class, 'join']);
Route::get('/games/sync', [GameController::class, 'sync']);
Route::get('/games', [GameController::class, 'index']);
Route::post('/games', [GameController::class, 'store']);
Route::get('/games/{game}', [GameController::class, 'show']);
Route::delete('/games/{game}', [GameController::class, 'destroy']);

// Leaderboard
Route::get('/leaderboard', [LeaderboardController::class, 'index']);
Route::get('/leaderboard/top-three', [LeaderboardController::class, 'topThreeLeaders']);

// Settings
//Route::get('/settings', [SettingsController::class, 'index']);

// Room Codes
Route::delete('/rooms/expired', [RoomCodeController::class, 'destroyExpiredRooms']);
Route::get('/rooms', [RoomCodeController::class, 'index']);
Route::post('/rooms', [RoomCodeController::class, 'store']);
Route::get('/rooms/{room}', [RoomCodeController::class, 'show']);
Route::delete('/rooms/{room}', [RoomCodeController::class, 'destroy']);
