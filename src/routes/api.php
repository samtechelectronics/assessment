<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// authenication routes
Route::post('/register', 'authenticationController@register');
Route::post('/login', 'authenticationController@login');
 // game routes 
 Route::resource('/games', 'gameController');

 // game version
 
Route::post('/game/addversion', 'gameController@addVersion');
Route::post('/game/editversion', 'gameController@editversion');
Route::post('/game/deleteversion/{id}', 'gameController@deleteversion');

// user Game controller


Route::get('/user/games', 'userGameController@getUserGames');
Route::post('/user/assigngame', 'userGameController@assignGameToUser');

// play games route


Route::post('/playgame/single', 'gamePlayController@playSinglesGame');
Route::post('/playgame/multiple', 'gamePlayController@playMultiplesGame');
Route::get('/generate', 'gamePlayController@generateRandomPlays');


// game statistics

Route::get('/playersStatistics', 'gameStatisticsContoller@getPlayer');
Route::post('/gameStatistics/bydate', 'gameStatisticsContoller@getGamePlayedByDate');
Route::post('/gameStatistics/bydateRange', 'gameStatisticsContoller@getGamePlayedByDateRange');
Route::get('/gameStatistics/top100bymonth', 'gameStatisticsContoller@top100PlayerbyMonth');
Route::get('/getPlays/{month}/{year}/{user_id}', 'gameStatisticsContoller@getTopPlayerGames');
