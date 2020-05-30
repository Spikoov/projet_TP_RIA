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
        if (request()->session()->has('selectedTeamId')) {
            $this->_idEquipe = intval(request()->session()->get('selectedTeamId'));
            $this->_idEquipe--;
        }

        $this->_equipes = array();
        $nbEquipes = DB::table('equipes')->count();

        for ($i=0; $i < $nbEquipes; $i++) {
            array_push($this->_equipes, new EquipeController($i));
        }
    }

    public function play()
    {
        // TODO: recruter des joueurs
        //      voir details joueurs
        //      style -> (selection joueurs [budget] / lors du changement n'afficher que le poste adéquat)
        //      Changement organisation (+ changement joueurs)

        if ($this->_idEquipe === NULL) {
            return redirect('/');
        }

        return view('game', [
            'classementEquipes' => $this->getClassement(),
            'equipe' => $this->_equipes[$this->_idEquipe],
            'nomEquipe' => $this->_equipes[$this->_idEquipe]->getNom(),
            'budgetEquipe' => $this->_equipes[$this->_idEquipe]->getBudget(),
            'titulaires' => $this->_equipes[$this->_idEquipe]->getTitulaireInfos(),
            'remplacants' => $this->_equipes[$this->_idEquipe]->getRemplacantInfos(),
            'autres' => $this->_equipes[$this->_idEquipe]->getAutresInfos()
        ]);
    }

    public function getClassement()
    {
        $classement = array();
        foreach ($this->_equipes as $e) {
            array_push($classement, [
                'points' => $e->getPoints(),
                'nom' => $e->getNom()
            ]);
        }

        rsort($classement);

        return $classement;
    }

    public function selectRemplacants()
    {
      $joueursSansContrat = new JoueurController();

      return view('selectRemplacants', [
          'joueurs' => $joueursSansContrat->getInfoSansContrat(),
          'classementEquipes' => $this->getClassement(),
          'equipe' => $this->_equipes[$this->_idEquipe],
          'nomEquipe' => $this->_equipes[$this->_idEquipe]->getNom(),
          'budgetEquipe' => $this->_equipes[$this->_idEquipe]->getBudget(),
          'titulaires' => $this->_equipes[$this->_idEquipe]->getTitulaireInfos(),
          'remplacants' => $this->_equipes[$this->_idEquipe]->getRemplacantInfos(),
          'autres' => $this->_equipes[$this->_idEquipe]->getAutresInfos()
      ]);
    }

    public function setRemplacants()
    {
      request()->validate([
          'idJoueur' => []
      ]);

      $this->_equipes[$this->_idEquipe]->setRemplacants(request('idJoueur'));
      $soustraireBudget = $this->getSommeNotes(request('idJoueur'));
      $this->_equipes[$this->_idEquipe]->updateBudget($soustraireBudget);

      return redirect('/game');
    }

    public function getSommeNotes($remplacants)
    {
      $notes = array();

      foreach ($remplacants as $rempls) {
        array_push($notes, DB::table('joueurs')->where('id', $rempls)->value('noteGlobale'));
      }
      return array_sum($notes);
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
        return redirect('/selectRemplacants');
    }

    public function newYear()
    {
        foreach ($this->$_joueursSansContrat as $joueur) {
            (new JoueurController)->newYear($id);
        }

        foreach ($this->_equipes as $equipe) {
            $equipe->newYear();
        }
    }

    public function displayChangerTitulaire()
    {
        return view('changerJoueurs', [
            'changer' => 'T',
            'classementEquipes' => $this->getClassement(),
            'equipe' => $this->_equipes[$this->_idEquipe],
            'nomEquipe' => $this->_equipes[$this->_idEquipe]->getNom(),
            'budgetEquipe' => $this->_equipes[$this->_idEquipe]->getBudget(),
            'titulaires' => $this->_equipes[$this->_idEquipe]->getTitulaireInfos(),
            'remplacants' => $this->_equipes[$this->_idEquipe]->getRemplacantInfos(),
            'autres' => $this->_equipes[$this->_idEquipe]->getAutresInfos()
        ]);
    }

    public function displayChangerRemplacant()
    {
        return view('changerJoueurs', [
            'changer' => 'R',
            'classementEquipes' => $this->getClassement(),
            'equipe' => $this->_equipes[$this->_idEquipe],
            'nomEquipe' => $this->_equipes[$this->_idEquipe]->getNom(),
            'budgetEquipe' => $this->_equipes[$this->_idEquipe]->getBudget(),
            'titulaires' => $this->_equipes[$this->_idEquipe]->getTitulaireInfos(),
            'remplacants' => $this->_equipes[$this->_idEquipe]->getRemplacantInfos(),
            'autres' => $this->_equipes[$this->_idEquipe]->getAutresInfos()
        ]);
    }

    public function changerJoueurs()
    {
        request()->validate([
            'idJoueurA' => [],
            'idJoueurB' => []
        ]);
        $idJoueurA = request('idJoueurA');
        $idJoueurB = request('idJoueurB');

        $this->_equipes[$this->_idEquipe]->echange($idJoueurA, $idJoueurB);
        return redirect('/game');
    }
}
