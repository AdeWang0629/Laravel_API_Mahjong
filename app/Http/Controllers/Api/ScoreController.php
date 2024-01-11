<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GamePlayer;
use App\Models\TotalScore;
use App\Models\Game;
use App\Models\NormalScore;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        \Log::info("index00000000000000000000000");
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
        $game_player = GamePlayer::where('game_id', $data['game_id'])->get();
     
        for ($i=0; $i < count($game_player); $i++) { 
            # code...
            $total_score = TotalScore::updateOrCreate(
                ['game_player_id' => $game_player[$i]->id],
                [
                    'score' => $data['score'][$i],
                    'score_money' => $data['scoreMoney'][$i],
                    'chip_number' => count($data['chipNumber']) > $i ? ($data['chipNumber'][$i] == NULL ? '' : $data['chipNumber'][$i]) : '',
                    'chip_money' => count($data['chipMoney']) > $i ? $data['chipMoney'][$i] : '0',
                ]
            );

            try {
                DB::beginTransaction();
            
                $delete_normal_score = NormalScore::where('game_player_id', $game_player[$i]->id)->delete();
            
                foreach ($data['rows'] as $key => $value) {
                    if (isset($value[$i]) && $value[$i] !== null) {
                        $normal_score = new NormalScore();
                        $normal_score->game_player_id = $game_player[$i]->id;
                        $normal_score->score = $value[$i];
                        $normal_score->save();
                    } else {
                        $normal_score = new NormalScore();
                        $normal_score->game_player_id = $game_player[$i]->id;
                        $normal_score->score = '';
                        $normal_score->save();
                    }
                }
            
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                // Handle the exception or log the error
            }
        }

        $game = Game::find($data['game_id']);
        $game->status = true;
        $game->save();

        return response()->json('success');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $game_player = GamePlayer::with('normal_scores')->with('total_scores')->where("game_id", $id)->get();
        \Log::info($game_player);
        return response()->json($game_player);
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
    }
}
