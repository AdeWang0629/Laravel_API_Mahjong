<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Player;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [ 'score', 'chip', 'event_date', 'status' ];

    public function players(){
        return $this->belongsToMany(Player::class, 'game_player');
    }
}