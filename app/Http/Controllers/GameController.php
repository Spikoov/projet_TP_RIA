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
    private $_saison;

    public function __construct()
    {
        //90 matchs / saisons
        //$this->_journee == 9 => matchs retours
        if (request()->session()->has('selectedTeamId')) {
            $this->_idEquipe = intval(request()->session()->get('selectedTeamId'));
            $this->_idEquipe--;
            $this->_journee = intval(request()->session()->get('journee'));
            $this->_saison = intval(request()->session()->get('saison'));
            $this->_tournament = request()->session()->get('tournament');
        }

        $this->_equipes = array();
        $nbEquipes = DB::table('equipes')->count();

        for ($i=0; $i < $nbEquipes; $i++) {
            array_push($this->_equipes, new EquipeController($i));
        }
    }

    public function play()
    {
        // TODO: match :
        //      algo pdt match -> full JS (retour des scores par form généré mdr)
        //      changement de joueurs pdt matchs
        //      front match
        //      algo match des autres (++simple)
        //      update du budget fin de la saison (à tester)
        //      appeler setNotes quand on changer de formation ou de joueurs et quand on recrute

        if ($this->_idEquipe === NULL) {
            return redirect('/');
        }

        return view('game', [
            'saison' => $this->_saison,
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
        $titulairesA = array();
        foreach ($this->_equipes[$this->_idEquipe]->getTitulaireInfos() as $titu) {
            $infos = DB::table('joueurs')->where('id', $titu['id'])->first();
            $infosJoueur = (array)$infos;

            array_push($titulairesA, $infosJoueur);
        }

        $remplA = array();
        foreach ($this->_equipes[$this->_idEquipe]->getRemplacantInfos() as $rempl) {
            $infos = DB::table('joueurs')->where('id', $rempl['id'])->first();
            $infosJoueur = (array)$infos;

            array_push($remplA, $infosJoueur);
        }

        $tituB = array();
        foreach ($this->_equipes[$this->_tournament[$this->_journee][0]['B'] - 1]->getTitulaireInfos() as $t) {
            $infos = DB::table('joueurs')->where('id', $t['id'])->first();
            $infosJoueur = (array)$infos;

            array_push($tituB, $infosJoueur);
        }

        return view('match', [
            'isDomi' => $this->_tournament[$this->_journee][0]['whereA'],
            'saison' => $this->_saison,
            'classementEquipes' => $this->getClassement(),
            'equipeA' => $this->_equipes[$this->_idEquipe],
            'equipeB' => $this->_equipes[$this->_tournament[$this->_journee][0]['B'] - 1],
            'nomEquipe' => $this->_equipes[$this->_idEquipe]->getNom(),
            'budgetEquipe' => $this->_equipes[$this->_idEquipe]->getBudget(),
            'titulairesA' => $titulairesA,
            'remplacants' => $remplA,
            'titulairesB' => $tituB
        ]);
    }

    public function finMatch()
    {
        //SCORES DOIT ÊTRE SOUS CE FORMAT
      /*$scores = array(
        [1, 2],
        [2, 2],
        [0, 0],
        [1, 0],
        [0, 3]
    );*/

      for ($i=0; $i < 5; $i++) {
        $idEquipe1 = 0;
        $idEquipe2 = 0;
        $endroit = $this->_tournament[$this->_journee][$i]['WhereA'];
        $stade = '';
        $nbSpect = 0;
        $points1 = 0;
        $points2 = 0;

        if($endroit == 'domi'){
          $idEquipe1 = $this->_tournament[$this->_journee][$i]['A'];
          $idEquipe2 = $this->_tournament[$this->_journee][$i]['B'];

          $points1 = $scores[$i][0];
          $points2 = $scores[$i][1];

          $stade = $this->_equipes[$idEquipe1 - 1]->getStade();
          $nbSpect = $this->_equipes[$idEquipe1 - 1]->getSpectateurs();
        }
        else {
          $idEquipe1 = $this->_tournament[$this->_journee][$i]['B'];
          $idEquipe2 = $this->_tournament[$this->_journee][$i]['A'];

          $score1 = $scores[$i][1];
          $score2 = $scores[$i][0];

          $stade = $this->_equipes[$idEquipe1 - 1]->getStade();
          $nbSpect = $this->_equipes[$idEquipe1 - 1]->getSpectateurs();
        }

        Match::create([
          'saison' => $this->_saison,
          'idClub1' => $idEquipe1,
          'idClub2' => $idEquipe2,
          'stade' => $stade,
          'scoreClub1' => $points1,
          'scoreClub2' => $point2,
          'nbSpectateurs' => $nbSpect
      ]);

      $budget1 = floor(($nbSpect * 70 / 100) * 0.002);
      $budget2 = floor(($nbSpect * 30 / 100) * 0.002);

      $this->_equipes[$idEquipe1 - 1]->ajoutBudget($budget1);
      $this->_equipes[$idEquipe2 - 1]->ajoutBudget($budget2);

      if($score1 == $score2){
        $point1 = 1;
        $point2 = 1;
      }
      else if ($score1 < $score2) {
        $point1 = 0;
        $point2 = 3;
      }
      else {
        $point1 = 3;
        $point2 = 0;
      }

      $this->_equipes[$idEquipe1 - 1]->updatePoints($point1);
      $this->_equipes[$idEquipe2 - 1]->updatePoints($point2);

      unset($scores[0]);
      sort($scores[0]);
      }
      $this->updateJournee();
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
        $this->_tournament = $this->matchAlgo();

        request()->session()->put('selectedTeamId', $this->_idEquipe);
        request()->session()->put('journee', 0);
        request()->session()->put('saison', 1);
        request()->session()->put('tournament', $this->_tournament);
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

        request()->session()->put('saison', $this->_saison + 1);
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
