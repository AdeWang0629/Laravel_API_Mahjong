<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('players')->insert([
            [ 'name' => '佐藤'],//sato
            [ 'name' => '鈴木'],//Suzuki
            [ 'name' => '田中'],//Tanaka
            [ 'name' => '中村']//Nakamura
        ]);
    }
}
