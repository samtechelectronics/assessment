<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\game;
use App\User;
use App\userGame;
use App\gameVersion;
use DB;
class userGameController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function getUserGames(){
        $user = Auth::User();
        $user['games']  = DB::table('user_games')->where('user_id' , $user->id)
            ->join('games', 'user_games.game_id', '=', 'games.id')
            ->join('game_versions', 'user_games.version_id', '=', 'game_versions.id')
            ->get();
        $response['code'] = 200;
        $response['data'] = $user;
        return response()->json($response , 200);  
     
    }
    // add a game to a user
    public function assignGameToUser(request $request){
        $validator = Validator::make($request->all(), [
            "game_id" =>  "required",
            "version_id" =>  "required",
        
      ]);

      if ($validator->fails()) {

           return $validator->messages();
      }
      $game = game::find($request->game_id);
      $version = gameVersion::find($request->game_id);
      if(!isset($game) || !(isset($version))){
        $response['code'] = 400;
        $response['error'] = 'Invalid game details';
        return response()->json($response , 200);  
      }

       $hasGame = userGame::where(['user_id' => Auth::User()->id , 'game_id' => $request->game_id , 'version_id' => $request->version_id])->first();
       if(isset($hasGame)){
        $response['code'] = 400;
        $response['error'] = 'User already have this game';
        return response()->json($response , 200);   
       }
       $userGame = new userGame;
       $userGame->user_id = Auth::User()->id;
       $userGame->game_id =$request->game_id;
       $userGame->version_id =$request->version_id;
       $userGame->save();
       $response['code'] = 200;
        $response['error'] = 'game added to user successfully';
        return response()->json($response , 200);  

    }
}
