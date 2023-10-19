<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NormalScore extends Model
{
    use HasFactory;
    
    protected $table = 'normal_score';

    protected $fillable = [ 'score', 'game_player_id', 'id' ];

    public function game_players(){
        return $this->belongsTo(GamePlayer::class);
    }
}