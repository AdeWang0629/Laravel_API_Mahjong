<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalScore extends Model
{
    use HasFactory;
    
    protected $table = 'total_score';

    protected $fillable = [ 'score', 'score_money', 'chip_number', 'chip_money', 'game_player_id', 'id' ];

    public function game_players(){
        return $this->belongsTo(GamePlayer::class, 'game_player_id');
    }
}