<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use URL;
use Validator;
use App\gamePlay;
use App\gamePlayPlayer;
class gameStatisticsContoller extends Controller
{
    //
    public function getPlayer(){
           $start = microtime(true);
           $players = User::with('games')->with('gameplays')->get();
            $response['code'] = 200;
            $response['players'] = $players;
            $time_elapsed_secs = microtime(true) - $start;
            return response()->json($response , 200)
            ->header('X-runtime', $time_elapsed_secs)
            ->header('X-memory', memory_get_usage());
            
    
    }
    //'Y-m-d'
    public function getGamePlayedByDate(request $request){
        $start = microtime(true);
        $validator = Validator::make($request->all(), [
            "date" =>  "required",
      ]);

      if ($validator->fails()) {

           return $validator->messages();
      }
   
    $gameplays = gamePlay::whereDate('game_plays.created_at', '=', date($request->date))
       ->leftJoin('users', 'game_plays.user_id', '=', 'users.id')
       ->leftJoin('games', 'game_plays.game_id', '=', 'games.id')
       ->leftJoin('game_versions', 'game_plays.version_id', '=', 'game_versions.id')
       ->select('game_plays.*', 'games.name', 'game_versions.version' , 'users.nickname  AS player_nickName' )
      ->get();
      foreach($gameplays as $gameplay) {
        $gameplay['players'] = 
        gamePlayPlayer::where('game_play_id' , $gameplays[0]->id)
        ->leftJoin('users', 'game_play_players.user_id', '=', 'users.id')
        ->select('game_play_players.*', 'users.name','users.email' , 'users.nickname  AS player_nickName' )
        ->get();
    } 
        $groupedGamePlay = $gameplays->groupBy('name'); 
       $response['code'] = 200;
       $response['gamePlays'] = $groupedGamePlay;
       $time_elapsed_secs = microtime(true) - $start;
       return response()->json($response , 200)
       ->header('X-runtime', $time_elapsed_secs)
       ->header('X-memory', memory_get_usage());
    }
    public function getGamePlayedByDateRange(request $request){
        $start = microtime(true);
        $validator = Validator::make($request->all(), [
            "start_date" =>  "required",
            "end_date" =>  "required",
      ]);

      if ($validator->fails()) {

           return $validator->messages();
      }
      $gameplays = gamePlay::whereDate('game_plays.created_at', '>=', date($request->start_date))
       ->whereDate('game_plays.created_at', '<=', date($request->end_date))
       ->leftJoin('users', 'game_plays.user_id', '=', 'users.id')
       ->leftJoin('games', 'game_plays.game_id', '=', 'games.id')
       ->leftJoin('game_versions', 'game_plays.version_id', '=', 'game_versions.id')
       ->select('game_plays.*', 'games.name', 'game_versions.version' , 'users.nickname  AS player_nickName' )
      ->get();
     foreach($gameplays as $gameplay) {
         $gameplay['players'] = 
         gamePlayPlayer::where('game_play_id' , $gameplays[0]->id)
         ->leftJoin('users', 'game_play_players.user_id', '=', 'users.id')
         ->select('game_play_players.*', 'users.name','users.email' , 'users.nickname  AS player_nickName' )
         ->get();
     } 
      $groupedGamePlay = $gameplays->groupBy('name'); 
      $response['code'] = 200;
      $response['gamePlays'] = $groupedGamePlay;
      $time_elapsed_secs = microtime(true) - $start;
      return response()->json($response , 200)
      ->header('X-runtime', $time_elapsed_secs)
      ->header('X-memory', memory_get_usage());
    }
    public function top100PlayerbyMonth(){
        $start = microtime(true);
    $base_url = URL::to('/');
   $monthGroup = gamePlay::select(
            
            DB::raw("DATE_FORMAT(created_at,'%M %Y') as months")
        )
        ->groupBy('months')
       ->get();
       for($i = 0 ; $i < count($monthGroup) ;$i++){
          $monthGroup[$i]['TopPlayers']  = DB::table('game_plays')
          ->where(DB::raw("DATE_FORMAT(created_at,'%M %Y') ") , $monthGroup[$i]['months'])
          ->select('*',
          DB::raw("CONCAT(  '/getPlays/',DATE_FORMAT(created_at,'%M/%Y'),'/' ,game_plays.user_id) as link"),
          DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
          DB::raw('COUNT(user_id) AS PlayTimes')
           )
          ->groupBy('user_id')
          ->orderBy('PlayTimes', 'DESC')
          ->limit(100)
          ->get();
       }
       
      $topPlayersGrouped = $monthGroup;
      $response['code'] = 200;
      $response['topPlayers'] = $topPlayersGrouped;
      $time_elapsed_secs = microtime(true) - $start;
      return response()->json($response , 200)
      ->header('X-runtime', $time_elapsed_secs)
      ->header('X-memory', memory_get_usage());

    }
    public function getTopPlayerGames($month, $year , $user_id){
        $start = microtime(true);
      $topPlayersGames = DB::table('game_plays')
         ->where('user_id' ,$user_id)
         ->where(DB::raw("DATE_FORMAT(game_plays.created_at,'%M %Y') ") , $month.' '.$year)
         ->leftJoin('users', 'game_plays.user_id', '=', 'users.id')
       ->leftJoin('games', 'game_plays.game_id', '=', 'games.id')
       ->leftJoin('game_versions', 'game_plays.version_id', '=', 'game_versions.id')
       ->select('game_plays.*', 'games.name', 'game_versions.version' , 'users.nickname  AS player_nickName' )
        ->get();
        $response['code'] = 200;
      $response['topPlayersGames'] = $topPlayersGames;
      $time_elapsed_secs = microtime(true) - $start;
      return response()->json($response , 200)
      ->header('X-runtime', $time_elapsed_secs)
      ->header('X-memory', memory_get_usage());
    }
}
