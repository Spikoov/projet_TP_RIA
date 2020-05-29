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

Route::get('/', 'LoadController@welcome');

Route::get('/teamSelector', 'GameController@teamSelectorDisplay');
Route::post('/teamSelector', 'GameController@teamSelectorAction');

Route::get('/game', 'GameController@play');
Route::get('/game/selectRemplacants', 'GameController@selectRemplacants');

Route::post('/setRemplacants', 'GameController@setRemplacants');
