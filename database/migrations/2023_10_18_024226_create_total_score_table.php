<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTotalScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_score', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('score');
            $table->string('score_money');
            $table->string('chip_number');
            $table->string('chip_money');
            $table->foreignId('game_player_id')->constrained('game_player')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('total_score');
    }
}
