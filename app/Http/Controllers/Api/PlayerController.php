<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\GamePlayer;
use App\Models\TotalScore;
use Carbon\Carbon;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = Player::all();
        
        return response()->json($players);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $player = new Player;

        $player->name = $request->p_name;

        $player->save();

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        \Log::info($request);
        // $player = Player::find($id);

        // $player->checked = !$player->checked;

        // $player->save();

        // return response()->json('success');
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
        \Log::info("123456789123456789");
        \Log::info($id);

        $player = Player::find($id);

        $player->delete();

        return response()->json('success');
    }

    public function getPlayerClass(Request $request){
        $requestData = $request->all();
        $filterArray = array();
        foreach ($requestData as $key => $row) {
            if ($row['checked']) {
                array_push($filterArray, $row);
            }
        }

        $gameIds = GamePlayer::select('game_id')->groupBy('game_id')->get();
        $selectGameIds = array();
        
        foreach ($gameIds as $key => $value) {
            $status = array();
            $selectGamePlayer = GamePlayer::where('game_id', $value->game_id)->get();
            if (count($selectGamePlayer) == count($filterArray)) {
                foreach ($selectGamePlayer as $key => $item) {
                    foreach ($filterArray as $key => $filter) {
                        if ($item['player_id'] == $filter['id']) {
                            array_push($status, true);
                        }
                    }
                }
                $newStatus = array_filter($status, function ($item) {
                    return $item == true;
                });
                if (count($newStatus) == count($filterArray)) {
                    array_push($selectGameIds, $value->game_id);
                }
            }
        }

        $grade_data = array();
        $grade_data_month = array();
        $grade_data_month_without_date = array();
        $grade_data_month_sum = array();

        if (!count($selectGameIds)) {
            return response()->json(['selectGameIds' => $selectGameIds], 200);
        }else{
            foreach ($selectGameIds as $key => $value) {
                $selectGamePlayer = GamePlayer::where('game_id', $value)->with('players')->with('total_scores')->with('games')->get();
                foreach ($selectGamePlayer as $key => $item) {
                    $date = Carbon::createFromFormat('Y-m-d', $item['games']['event_date']);
                    $formattedDate = $date->format('Y-m');
                    
                    if (!$item['total_scores']) {
                        return response()->json(['selectGameIds' => []], 200);
                    }else{
                        $newItem = [
                            'name' => $item['players']['name'],
                            'scores' => $item['total_scores']['chip_money'],
                            'date' => $formattedDate
                        ];
                        array_push($grade_data, $newItem);
                    }
                }
            }
            
            $uniqueDates = array_unique(array_column($grade_data, 'date'));
            foreach ($uniqueDates as $key => $valueDate) {
                $filterDateArray = array_filter($grade_data, function($item) use ($valueDate){
                    return $item['date'] == $valueDate;
                });
                $dateItem = [
                    'date' => $valueDate
                ];
                $uniqueNames = array_unique(array_column($filterDateArray, 'name'));
                foreach ($uniqueNames as $key => $valueName) {
                    $filterNameArray = array_filter($filterDateArray, function($item) use ($valueName){
                        return $item['name'] == $valueName;
                    });
                    $scores = 0;
                    foreach ($filterNameArray as $key => $valueScore) {
                        $newScores = str_replace(',', '', $valueScore['scores']); // Remove commas from the value
                        $scores += $newScores;
                    }
                    $nameItem = [
                        'name' => $valueName,
                        'scores' => $scores
                    ];
                    array_push($dateItem, $nameItem);
                    array_push($grade_data_month_without_date, $nameItem);
                }
                array_push($grade_data_month, $dateItem);
            }

            $newUniqueNames = array_unique(array_column($grade_data_month_without_date, 'name'));
            foreach ($newUniqueNames as $key => $valueName) {
                $filterNameArray = array_filter($grade_data_month_without_date, function($item) use ($valueName){
                    return $item['name'] == $valueName;
                });
                $scores = 0;
                foreach ($filterNameArray as $key => $valueScore) {
                    $scores += $valueScore['scores'];
                }
                $nameItem = [
                    'name' => $valueName,
                    'scores' => $scores
                ];
                array_push($grade_data_month_sum, $nameItem);
            }

            return response()->json(['selectGameIds' => $selectGameIds, 'grade_data_month' => $grade_data_month, 'grade_data_month_sum' => $grade_data_month_sum], 200);
        }
    }

    public function getPlayerMember(Request $request){
        $game_player_data = GamePlayer::where('player_id', $request['id'])->get();
        $old_player_data = array();

        $grade_data_player = array();
        $grade_data_player_sum = 0;

        if (count($game_player_data)) {
            foreach ($game_player_data as $key => $value) {
                $total_score_data = TotalScore::where('game_player_id', $value['id'])->first();
                $date = $total_score_data['updated_at'];
                $convertDate = Carbon::parse($date)->format('Y-m');
                $convertData = [
                    'date' => $convertDate,
                    'scores' => $total_score_data['chip_money'],
                ];
                array_push($old_player_data, $convertData);
            }

            $uniqueDates = array_unique(array_column($old_player_data, 'date'));
            foreach ($uniqueDates as $key => $valueDate) {
                $filterDateArray = array_filter($old_player_data, function($item) use ($valueDate){
                    return $item['date'] == $valueDate;
                });
                $scores = 0;
                foreach ($filterDateArray as $key => $valueScore) {
                    $newScores = str_replace(',', '', $valueScore['scores']);
                    $scores += $newScores;
                }
                $dateItem = [
                    'date' => $valueDate,
                    'scores' => $scores
                ];
                array_push($grade_data_player, $dateItem);
                $grade_data_player_sum += $scores;
            }
            \Log::info($grade_data_player);
            \Log::info($grade_data_player_sum);
            return response()->json(['dataState' => 1, 'grade_data_player' => $grade_data_player, 'grade_data_player_sum' => $grade_data_player_sum], 200);
        }else{
            return response()->json(['dataState' => 0], 200);
        }
    }
}