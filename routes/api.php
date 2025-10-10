<?php

use App\Http\Controllers\Api\GameController;

Route::get('/games', [GameController::class, 'index']);
Route::post('/games', [GameController::class, 'store']);
