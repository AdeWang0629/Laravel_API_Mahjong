<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\ScoreController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('hello', function(){
    return response()->json("1234567890");
});

Route::apiResource('player', PlayerController::class);
Route::apiResource('game', GameController::class);
Route::apiResource('score', ScoreController::class);

Route::post('/player/check', [PlayerController::class, 'update']);

Route::post('/game/score', [GameController::class, 'game_score_update']);

Route::post('/game/chip', [GameController::class, 'game_chip_update']);