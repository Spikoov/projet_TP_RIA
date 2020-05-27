<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    private $_idEquipe;
    private $_equipes;
    private $_joueursSansContrat;

    public function __construct()
    {
        //90 matchs / saisons
        $this->_equipes = array();
        $nbEquipes = DB::table('equipes')->count();

        for ($i=0; $i < $nbEquipes; $i++) {
            array_push($this->_equipes, new EquipeController($i));
        }
    }

    public function play()
    {
        // TODO: update à chaque fin de saisons Joueurs -> (age, duree contrat) / Equipe -> (budget)
        //      update des salaires en fonction du role (titu 20, rempl 5 ou autre 0)
        //      Afficher la ligue + saisons etc
        //      affiche son equipe (nom club, budget, joueurs(nom, salaire, dureeContrat, note, poste), formation)
        //      affiche liste des joueurs(nom, salaire, dureeContrat, note, poste) sans contrat -> qu'il en prenne 3

        request()->session()->get('selectedTeamId');

        return view('game');
    }

    public function teamSelectorDisplay()
    {
        return view('team-selector', [
            'equipes' => $this->_equipes
        ]);
    }

    public function teamSelectorAction()
    {
        request()->validate([
            'selectedEquipe' => []
        ]);
        $this->_idEquipe = request('selectedEquipe');

        request()->session()->put('selectedTeamId', $this->_idEquipe);
        return redirect('/game');
    }

    public function newYear()
    {
        foreach ($this->_equipes as $equipe) {
            $equipe->newYear();
        }

        foreach ($this->$_joueursSansContrat as $joueur) {
            (new JoueurController)->newYear($id);
        }
    }
}
