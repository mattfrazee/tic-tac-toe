<?php

use App\Http\Controllers\Api\GameController;

Route::get('/games', [GameController::class, 'index']);
Route::post('/games', [GameController::class, 'store']);
Route::get('/games/{game}', [GameController::class, 'show']);
Route::delete('/games/clear', [GameController::class, 'clearScores']);
Route::delete('/games/{game}', [GameController::class, 'destroy']);
