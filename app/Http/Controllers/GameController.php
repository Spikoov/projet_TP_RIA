<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    private $_idEquipe;
    private $_equipes;
    private $_joueursSansContrat;
    private $_journee;
    private $_tournament;

    public function __construct()
    {
        //90 matchs / saisons
        //$this->_journee == 9 => matchs retours
        if (request()->session()->has('selectedTeamId')) {
            $this->_idEquipe = intval(request()->session()->get('selectedTeamId'));
            $this->_idEquipe--;
            $this->_journee = intval(request()->session()->get('journee'));
        }

        $this->_equipes = array();
        $nbEquipes = DB::table('equipes')->count();

        for ($i=0; $i < $nbEquipes; $i++) {
            array_push($this->_equipes, new EquipeController($i));
        }

        if(isset($this->_idEquipe))
            $this->_tournament = $this->matchAlgo();
    }

    public function play()
    {
        // TODO: match :
        //      update du budget en focntion des spec (domi, ext)
        //      update de l'affichage de la saison
        //      update du budget fin de la saison

        if ($this->_idEquipe === NULL) {
            return redirect('/');
        }

        return view('game', [
            'prochainMatch' => $this->_equipes[$this->_tournament[$this->_journee][0]['B'] - 1]->getNom(),
            'classementEquipes' => $this->getClassement(),
            'equipe' => $this->_equipes[$this->_idEquipe],
            'nomEquipe' => $this->_equipes[$this->_idEquipe]->getNom(),
            'budgetEquipe' => $this->_equipes[$this->_idEquipe]->getBudget(),
            'titulaires' => $this->_equipes[$this->_idEquipe]->getTitulaireInfos(),
            'remplacants' => $this->_equipes[$this->_idEquipe]->getRemplacantInfos(),
            'autres' => $this->_equipes[$this->_idEquipe]->getAutresInfos()
        ]);
    }

    public function debutMatch()
    {
        if ($this->_tournament[$this->_journee][0]['whereA'] === 'domi') {
            $this->_equipes[$this->_idEquipe]->getSpectateurs();
        }
        else {
            $this->_equipes[$this->_tournament[$this->_journee][0]['B'] - 1]->getSpectateurs();
        }

        return view('match', [
            'classementEquipes' => $this->getClassement(),
            'equipe' => $this->_equipes[$this->_idEquipe],
            'nomEquipe' => $this->_equipes[$this->_idEquipe]->getNom(),
            'budgetEquipe' => $this->_equipes[$this->_idEquipe]->getBudget(),
            'titulaires' => $this->_equipes[$this->_idEquipe]->getTitulaireInfos(),
            'remplacants' => $this->_equipes[$this->_idEquipe]->getRemplacantInfos(),
            'autres' => $this->_equipes[$this->_idEquipe]->getAutresInfos()
        ]);
    }

    public function matchAlgo()
    {
        $all = array();
        foreach ($this->_equipes as $key ) {
            array_push($all, $key->getId());
        }
        $domiOrExte = array();

        for ($i=0; $i < 9; $i++) {
            $jour = array();
            for ($j=0; $j < 5; $j++) {
                $jour[$j] = array('domi', 'exte');
            }
            $domiOrExte[$i] = $jour;
        }

        $player = $this->_idEquipe + 1;
        $ex = $all[0];
        $k = array_search($player, $all);
        $all[$k] = $ex;
        $all[0] = $player;

        $first = array();
        $last = array();
        $tournament = array();

        for ($i=0; $i < count($this->_equipes)/2; $i++) {
            $first[$i] = $all[$i];
            $last[$i] = $all[(count($all) - $i) - 1];
        }

        //ALLER
        for ($j=0; $j < count($this->_equipes) - 1; $j++) {
            $ronde = array();
            for ($i=0; $i < count($this->_equipes)/2; $i++) {
                shuffle($domiOrExte[$j][$i]);
                array_push($ronde, [
                    'A' => $first[$i],
                    'B' => $last[$i],
                    'whereA' => array_pop($domiOrExte[$j][$i])
                ]);
            }
            array_push($tournament, $ronde);

            $tmp0 = array($first[0], $last[0], $first[1], $first[2], $first[3]);
            $tmp1 = array($last[1], $last[2], $last[3], $last[4], $first[4]);
            $first = $tmp0;
            $last = $tmp1;
        }

        //RETOUR
        for ($j=0; $j < count($this->_equipes) - 1; $j++) {
            $ronde = array();
            for ($i=0; $i < count($this->_equipes)/2; $i++) {
                shuffle($domiOrExte[$j][$i]);
                array_push($ronde, [
                    'A' => $first[$i],
                    'B' => $last[$i],
                    'whereA' => array_pop($domiOrExte[$j][$i])
                ]);
            }
            array_push($tournament, $ronde);

            $tmp0 = array($first[0], $last[0], $first[1], $first[2], $first[3]);
            $tmp1 = array($last[1], $last[2], $last[3], $last[4], $first[4]);
            $first = $tmp0;
            $last = $tmp1;
        }

        return $tournament;
    }

    public function updateJournee()
    {
        request()->session()->put('journee', $this->_journee + 1);
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

    public function selectEffectif()
    {
      $joueursSansContrat = new JoueurController();

      return view('selectEffectif', [
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

    public function setEffectif()
    {
      request()->validate([
          'idJoueur' => []
      ]);

      $this->_equipes[$this->_idEquipe]->setAutres(request('idJoueur'));
      $soustraireBudget = $this->getSommeNotes(request('idJoueur'));
      $this->_equipes[$this->_idEquipe]->updateBudget($soustraireBudget);

      return redirect('/game');
    }

    public function getSommeNotes($joueurs)
    {
      $notes = array();

      foreach ($joueurs as $joueur) {
        array_push($notes, floor(DB::table('joueurs')->where('id', $joueur)->value('noteGlobale') / 2));
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
        request()->session()->put('journee', 0);
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

    public function changerFormation()
    {
      request()->validate([
          'nForm' => [],
      ]);
      $nForm = request('nForm');

      $cinquieme = $this->testFormation($nForm);

      $this->_equipes[$this->_idEquipe]->setOrganisation($nForm);

      return view('changerFormation', [
          'poste' => $cinquieme,
          'classementEquipes' => $this->getClassement(),
          'equipe' => $this->_equipes[$this->_idEquipe],
          'nomEquipe' => $this->_equipes[$this->_idEquipe]->getNom(),
          'budgetEquipe' => $this->_equipes[$this->_idEquipe]->getBudget(),
          'titulaires' => $this->_equipes[$this->_idEquipe]->getTitulaireInfos(),
          'remplacants' => $this->_equipes[$this->_idEquipe]->getRemplacantInfos(),
          'autres' => $this->_equipes[$this->_idEquipe]->getAutresInfos()
      ]);
    }

    public function testFormation($formation)
    {
      if($defense = $formation[0] == '2')
        return 'Defense';
      else if($milieu = $formation[2] == '2')
        return 'Milieu';
      return 'Attaque';
    }
}
