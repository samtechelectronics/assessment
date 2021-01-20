<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\game;
use App\gameVersion;
class gameController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:api');

    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $games  = game::with('versions')->get();
      $response['code'] = 200;
      $response['games'] = $games;
      return response()->json($response , 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" =>  "required|string|unique:games",
      ]);

      if ($validator->fails()) {

           return $validator->messages();
      }
      $game = new game;
      $game->name = $request->name;
      $game->save();
      $response['code'] = 200;
      $response['message'] = 'Game Created Successfully';
      return response()->json($response , 200);  
    }

  

 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $validator = Validator::make($request->all(), [
            "name" =>  "required|string|unique:games",
      ]);

      if ($validator->fails()) {

           return $validator->messages();
      }
      $game = game::findOrFail($id);
      $game->name = $request->name;
      $game->save();
      $response['code'] = 200;
      $response['message'] = 'Game Updated Successfully';
      return response()->json($response , 200);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $game = game::find($id);
        if(!isset($game)){
            $response['code'] = '404';
            $response['error'] = 'Invalid Game ID';
            return response()->json($response , 404); 
        }
        $versions  = $game->versions;
        foreach($versions as $version){
            $version->delete();
        }
        $game->delete();
        $response['code'] = 200;
        $response['message'] = 'Game Deleted Successfully';
        return response()->json($response , 200); 
    }
    public function  addVersion(request $request){
        $validator = Validator::make($request->all(), [
            "game_id" =>  "required",
            "version_name" =>  "required",
      ]);

      if ($validator->fails()) {

           return $validator->messages();
      } 
      $game = game::find($request->game_id);
      if(!isset($game)){
          $response['code'] = '404';
          $response['error'] = 'Invalid Game ID';
          return response()->json($response , 404); 
      } 
      $gameVersion =  new gameVersion;
      $gameVersion->game_id = $request->game_id;
      $gameVersion->version = $request->version_name;
      $gameVersion->save();
      $response['code'] = '200';
      $response['message'] = 'Version Created Successfully';
      return response()->json($response , 200); 


    }
    public function  deleteVersion($id){
      $version =  gameVersion::find($id) ;
      if(!isset($version)){
          $response['code'] = '404';
          $response['error'] = 'Invalid Version ID';
          return response()->json($response , 404); 
      }
      $version->delete();
      $response['code'] = '200';
      $response['message'] = 'Version Deleted Successfully';
      return response()->json($response , 200);

    }
    public function  editVersion(request $request){
        $validator = Validator::make($request->all(), [
            "version_id" =>  "required",
            "version_name" =>  "required",
      ]);

      if ($validator->fails()) {

           return $validator->messages();
      }
      $version = gameVersion::find($request->version_id);
      if(!isset($version)){
        $response['code'] = '404';
        $response['error'] = 'Invalid Version ID';
        return response()->json($response , 404); 
    }
    $version->version = $request->version_name;
    $version->save();
    $response['code'] = '200';
        $response['error'] = 'Version Updated Successfully';
        return response()->json($response , 200); 
    }

}
