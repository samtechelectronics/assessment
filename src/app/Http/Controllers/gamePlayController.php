<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use Carbon\Carbon;
use App\game;
use App\gamePlay;
use App\userGame;
use App\User;
class gamePlayController extends Controller
{
    //
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }
    public function generateRandomPlays(){
        
    }
    public function playSinglesGame(request $request){
        $validator = Validator::make($request->all(), [
            "game_id" =>  "required",
            "version_id" =>  "required",
      ]);

      if ($validator->fails()) {

           return $validator->messages();
      }
      $hasgame = userGame::where(['user_id' => Auth::User()->id ,'game_id' => $request->game_id , 'version_id' => $request->version_id])->first(); 
      if(!isset($hasgame)){
        $response['code'] = 400;
        $response['error'] = 'You dont have this game';
        return response()->json($response , 200);                    
      }
      $TodayGamePlay = gamePlay::where(['user_id' => Auth::User()->id ,'game_id' => $request->game_id ,'version_id' => $request->version_id])->whereDate('created_at', Carbon::today())->first();
    if(isset($TodayGamePlay)){
        $TodayGamePlay->play_count = $TodayGamePlay->play_count + 1;
        $TodayGamePlay->save();
       

    } 
    else{
    $gamePlay = new gamePlay;
    $gamePlay->game_id = $request->game_id;
    $gamePlay->user_id =Auth::User()->id;
    $gamePlay->version_id = $request->version_id;
    $gamePlay->game_type = 'single';
    $gamePlay->play_count = 1;
    $gamePlay->save();
    $player = new gamePlayPlayer;
    $player->game_play_id = $gamePlay->id;
    $player->user_id = Auth::User()->id;
    $player->save();
    }

    $response['code'] = 200;
    $response['message'] = 'Game Play Registered';
    return response()->json($response , 200); 

    }
    public function playMultiplesGame(request $request){
        $validator = Validator::make($request->all(), [
            "game_id" =>  "required",
            "version_id" =>  "required",
            "users_id" => "required|array|between:1,3"
      ]);

      if ($validator->fails()) {

           return $validator->messages();
      } 
      for($i = 0 ;$i < count($request->users_id) ; $i++){
        $user = user::find($request->users_id[$i]);
        if(!isset($user)){
            $response['code'] = 400;
            $response['error'] = 'User with ID '.$request->users_id[$i]." not registered";
            return response()->json($response , 200);    
        }
        $hasGame = userGame::where(['user_id' => $user->id ,'game_id' => $request->game_id , 'version_id' => $request->version_id])->first(); 
        if(!isset($hasGame)){
            $response['code'] = 400;
            $response['error'] = 'User with ID '.$request->users_id[$i]." does not have the game";
            return response()->json($response , 200);   
        }
       } 
       return 'valid';
       $TodayGamePlay = gamePlay::where(['user_id' => Auth::User()->id ,'game_id' => $request->game_id ,'version_id' => $request->version_id])->whereDate('created_at', Carbon::today())->first();
       $TodayGamePlayers = gamePlayPlayer::where('game_play_id' , $TodayGamePlay->id)->get();
       if(isset($TodayGamePlay)){
           $samePlayers = true;
           if(count($TodayGamePlayers) ==( count($request->users_id) -1)){
               //same user count 
               foreach($TodayGamePlayers as $player){
                   if($player->user_id == Auth::User()->id){
                    continue;
                   }
                   if(!in_array($player->user_id, $request->users_id)){
                    $samePlayers = false;
                   }
               }
           }else{
               $samePlayers =false;
           }
           if($samePlayers){
            $TodayGamePlay->play_count = $TodayGamePlay->play_count + 1;
            $TodayGamePlay->save();
           }
           
          
   
       }
       else{
        $gamePlay = new gamePlay;
        $gamePlay->game_id = $request->game_id;
        $gamePlay->user_id =Auth::User()->id;
        $gamePlay->version_id = $request->version_id;
        $gamePlay->game_type = 'multiple';
        $gamePlay->play_count = 1;
        $gamePlay->save();
        $player = new gamePlayPlayer;
        $player->game_play_id =  $gamePlay->id;
        $player->user_id = Auth::User()->id;
        $player->save();
       for($i = 0 ; $i < count($request->users_id) ;$i++){
        $player = new gamePlayPlayer;
        $player->game_play_id =  $gamePlay->id;
        $player->user_id = $request->users_id[$i];
        $player->save();
       }
 
       }

    $response['code'] = 200;
    $response['message'] = 'Game Play Registered';
    return response()->json($response , 200); 
    }
    public function validateUserHasGame($user , $game){
    //   $userGame = userGame
    }
}
