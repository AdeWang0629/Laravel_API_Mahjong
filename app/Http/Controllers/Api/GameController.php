<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Game;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $game = Game::with('players')->orderBy('created_at', 'desc')->get();
        return response()->json($game);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->body;
        \Log::info($data);

        $playerData = $data['playerlist'];

        $gameData = [
            'score' => $data['score'] / 10,
            'chip' => $data['chip'],
            'event_date' => $data['event_date'],
        ];
        $game = Game::create($gameData);
        foreach ($playerData as $key => $player) {
            # code...
            if ($player['checked']) {
                # code...
                $game->players()->attach($player['id']);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //MpJJ
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        \Log::info($id);
        $game = Game::find($id);

        $game->players()->detach();

        $game->delete();
    }

    public function game_score_update(Request $request)
    {
        $game = Game::find($request->body['game_id']);
        
        $game->score = $request->body['game_score'];

        $game->save();

        return response()->json(['game_score' => $game->score], 200);
    }

    public function game_chip_update(Request $request)
    {
        $game = Game::find($request->body['game_id']);
        
        $game->chip = $request->body['game_chip'];

        $game->save();

        return response()->json(['game_chip' => $game->chip], 200);
    }
}
