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
Route::get('/selectRemplacants', 'GameController@selectRemplacants');

Route::post('/setRemplacants', 'GameController@setRemplacants');

Route::get('/selectEffectif', 'GameController@selectEffectif');
Route::post('/setEffectif', 'GameController@setEffectif');

Route::get('/changerTitulaire', 'GameController@displayChangerTitulaire');
Route::get('/changerRemplacant', 'GameController@displayChangerRemplacant');
Route::post('/changerJoueurs', 'GameController@changerJoueurs');

Route::post('/changerFormation', 'GameController@changerFormation');

Route::get('detailsJoueurs/{id}', 'JoueurController@detailJoueur');

Route::get('/match', 'GameController@debutMatch');
