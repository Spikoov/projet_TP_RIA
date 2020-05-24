<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\JoueurController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EquipeController;


class LoadController extends Controller
{
    public function welcome()
    {
        //DB::table('joueurs')->truncate();
        //DB::table('clubs')->truncate();
        //DB::table('villes')->truncate();
        //DB::table('historique_clubs')->truncate();
        //DB::table('equipes')->truncate();
        //DB::table('matches')->truncate();

        //generate 200 players
        for ($i=0; $i < 200; $i++) {
            (new JoueurController)->generateJoueur();
        }

        //generate 10 clubs
        for ($i=0; $i < 10; $i++) {
            (new ClubController)->generateClub();
        }

        return view('welcome');
    }
}
