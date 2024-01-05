<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'checked' ];

    public function games(){
        return $this->belongsToMany(Game::class, 'game_player');
    }

    public function game_players(){
        return $this->hasOne(GamePlayer::class, 'player_id');
    }
}