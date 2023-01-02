<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('game')->group(function() {
    Route::get('/', 'GameController@index');
    Route::post('/pin', 'GameController@postPin');
    Route::get('/identify', 'GameController@identify');
    Route::post('/start', 'GameController@start');
    Route::get('/wait', 'GameController@wait');
    Route::get('/lobby/{id}', 'GameController@lobby')->where('id','[0-9]+');
    Route::post('/quiz/{id}', 'GameController@startGame')->where('id','[0-9]+');
    Route::get('/quiz/{id}', 'GameController@quiz')->where('id','[0-9]+');
});
