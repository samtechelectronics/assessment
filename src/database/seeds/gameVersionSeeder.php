<?php

use Illuminate\Database\Seeder;
use App\game;
use App\gameVersion;
class gameVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games =game::all();
        $gameVersions = [];
        foreach($games as $game){
            $versionStart = 2010;
           for($i = 0 ; $i <= 10 ; $i++){

            $gameVersions[] = [
                'game_id' => $game->id,
                'version' => $versionStart +$i,
                'created_at' =>  $game->created_at->addYear($i),
                'updated_at' =>  $game->created_at->addYear($i)
            ];
           
           }
        }
        gameVersion::insert($gameVersions);    
       }
}
