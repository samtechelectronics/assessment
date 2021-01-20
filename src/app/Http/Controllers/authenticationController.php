<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
class authenticationController extends Controller
{
    //
     //
     public function register(request $request){
        $validator = Validator::make($request->all(), [
                    "name" =>  "required",
                    "nickname" =>  "required|string|unique:users",
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6|'
              ]);
       
              if ($validator->fails()) {
       
                   
                    $response['code'] = 400;
                    $response['error'] = $validator->messages();
                    return response()->json($response ,200);
              }
              else {
           $user = new user;
           $user->name = $request->name;
           $user->nickname = $request->nickname;
           $user->email = $request->email;
           $user->password = bcrypt($request->password);
           $user->save();
       
       
           $response['code'] = 200;
           $response['message'] = "User registeration was successfully.";
           $response['user'] = $user;
           return response()->json($response ,200);
        }
       
         }
         public function login(request $request){
            $validator = Validator::make($request->all(), [
                "email" =>  "required",
                "password" =>  "required",
              
          ]);
   
          if ($validator->fails()) {
   
               return $validator->messages();
          }
          
          $data = [
               'grant_type'=> 'password',
               'client_id'=> 2,
               'client_secret'=> 'TOez2kOVEjngle4k7NPa0lCydlhmJekZL8NUKC6Z',
               'username'=> $request->email,
               'password'=> $request->password,
               'scopes'=> '[*]'
           ];
          $request = Request::create('/oauth/token', 'POST', $data);
          return app()->handle($request);

         }
    
}
