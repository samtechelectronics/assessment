<?php

use Illuminate\Database\Seeder;
use App\game;
use App\gameVersion;
use App\userGame;
use App\gamePlayPlayer;
use App\gamePlay;
use App\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', -1);
        // $this->call(UserSeeder::class);
        // $this->call(gameSeeder::class);
        // $this->call(gameVersionSeeder::class);
        // assign games to users
        
        // $games =game::all();
        // $count = 0;
       
        // foreach($games as $game){
        //     $versions = $game->versions;
        //     $users = User::all()->random(2000);
        //     for($i = 0 ; $i < 2000 ; $i++){
        //         $count =$count + $i;
        //       foreach($versions as $version){
        //         $userGame = new userGame; 
        //         $userGame->user_id = $users[$i]->id;
        //         $userGame->game_id = $game->id;
        //         $userGame->version_id = $version->id;
        //         $userGame->created_at = $version->created_at;
        //         $userGame->save(); 
        //       }
        //     }
            

        // }
        // generate game play days
        echo "starting";
        $gamePlays = [];
        $players = [];
        for($i = 0 ; $i < 349 ; $i++){
            $versions = gameVersion::all();
            foreach($versions as $version){
                
                $usergames = userGame::where('version_id' , $version->id)->take(300)->get();
                foreach($usergames as $usergame){
                    $gamePlays[] = [
                        'game_id' => $version->game_id,
                        'user_id' => $usergame->user_id,
                        'version_id' => $version->id,
                        'game_type' => 'single',
                        'play_count' =>  1,
                        'created_at' =>  $version->created_at->addDay($i),
                        'updated_at' =>  $version->created_at->addDay($i)
                    ];
                    // $gamePlay->game_id = $version->game_id;
                    // $gamePlay->user_id = $usergame->user_id;
                    // $gamePlay->version_id = $version->id;
                    // $gamePlay->game_type = 'single';
                    // $gamePlay->play_count = 1;
                    // $gamePlay->created_at =  $version->created_at->addDay($i);
                    // $gamePlay->save();
                    
                    // $player->game_play_id = $gamePlay->id;
                    // $player->user_id = $usergame->user_id;
                    // $player->created_at =  $version->created_at->addDay($i);
                    // $player->save();
                }
            }
            }
            echo "done collation";
            echo "data count ".count($gamePlays);
            $chunks = array_chunk($gamePlays , 5000);
            foreach($chunks as $key => $chunk){
                gamePlay::insert($chunk); 
                echo "done sending badge ".$key;
            }
            echo "done sending";

    }
}
