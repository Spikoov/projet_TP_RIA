<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\JoueurController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EquipeController;
use Illuminate\Support\Facades\DB;


class LoadController extends Controller
{
    public function welcome()
    {
        request()->session()->forget('selectedTeamId');
        request()->session()->forget('journee');
        request()->session()->forget('saison');

        DB::table('joueurs')->truncate();
        DB::table('clubs')->truncate();
        DB::table('villes')->truncate();
        DB::table('historique_clubs')->truncate();
        DB::table('equipes')->truncate();
        DB::table('matches')->truncate();
        DB::table('titulaires')->truncate();
        DB::table('remplacants')->truncate();
        DB::table('effectif_autres')->truncate();

        //generate 200 players
        for ($i=0; $i < 200; $i++) {
            (new JoueurController)->generateJoueur();
        }

        //generate 10 clubs
        for ($i=0; $i < 10; $i++) {
            (new ClubController)->generateClub();
            (new EquipeController($i))->generateEquipe();
        }

        return view('welcome');
    }
}
