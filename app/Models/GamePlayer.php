<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{
    use HasFactory;

    protected $table = 'game_player';

    protected $fillable = [ 'game_id', 'player_id', 'id' ];

    public function total_scores(){
        return $this->hasOne(TotalScore::class, 'game_player_id');
    }

    public function normal_scores(){
        return $this->hasMany(NormalScore::class);
    }

    public function players(){
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function games(){
        return $this->belongsTo(Game::class, 'game_id');
    }
}
