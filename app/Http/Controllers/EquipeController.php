<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipe;
use App\Titulaire;
use App\Remplacant;
use App\EffectifAutre;
use Illuminate\Support\Facades\DB;

class EquipeController extends Controller
{
    private $_id;
    private $_titulaires;
    private $_remplacants;
    private $_effectifAutres;
    private $_organisation;
    private $_notes;

    public function __construct($lastId)
    {
        $this->_id = $lastId + 1;

        $titulaires = DB::table('titulaires')->select('idT1', 'idT2', 'idT3', 'idT4', 'idT5')->where('idEquipe', $this->_id)->get();
        if (isset($titulaires[0])) {
            $this->_titulaires = array(
              'T1' => $titulaires[0]->idT1,
              'T2' => $titulaires[0]->idT2,
              'T3' => $titulaires[0]->idT3,
              'T4' => $titulaires[0]->idT4,
              'T5' => $titulaires[0]->idT5
            );

            $remplacants = DB::table('remplacants')->select('idR1', 'idR2', 'idR3')->where('idEquipe', $this->_id)->get();

            if (isset($remplacants[0])) {
                $this->_remplacants = array(
                  'R1' => $remplacants[0]->idR1,
                  'R2' => $remplacants[0]->idR2,
                  'R3' => $remplacants[0]->idR3
                );

                $this->_effectifAutres = array();
                $autres = DB::table('effectif_autres')->select('idJoueur')->where('idEquipe', $this->_id)->get();
                for ($i=0; $i < count($autres); $i++) {
                    if (isset($autres[$i])) {
                        array_push($this->_effectifAutres, $autres[$i]->idJoueur);
                    }
                }
            }

            $formation = DB::table('equipes')->where('id', $this->_id)->value('organisation');
            $this->_organisation = $formation;

            $noteAbs = DB::table('equipes')->where('id', $this->_id)->value('noteAbsolue');
            $noteParti = DB::table('equipes')->where('id', $this->_id)->value('notePartielle');
            $this->_notes = array(
              'absolue' => $noteAbs,
              'partielle' => $noteParti
            );
        }
    }

    public function generateEquipe()
    {
        $this->_organisation = $this->generateOrga();
        $this->_titulaires = $this->generateTitulaires();

        $this->insert();
        $this->setNotes();
    }

    public function generateOrga()
    {

        $orgas = ["1-2-1", "2-1-1", "1-1-2"];

        $orga = $orgas[rand(0, count($orgas) - 1)];

        return $orga;
    }

    public function generateTitulaires()
    {
        $gardiens = DB::table('joueurs')->select('id')->where([
            ['poste', 'gardien'],
            ['sousContrat', '0']
        ])->get();
        $defs = DB::table('joueurs')->select('id')->where([
            ['poste', 'defense'],
            ['sousContrat', '0']
        ])->get();
        $mls = DB::table('joueurs')->select('id')->where([
            ['poste', 'milieu'],
            ['sousContrat', '0']
        ])->get();
        $atqs = DB::table('joueurs')->select('id')->where([
            ['poste', 'attaque'],
            ['sousContrat', '0']
        ])->get();

        if ($this->_organisation === "1-2-1") {
            $mlId = array();
            foreach ($mls as $ml) {
                array_push($mlId, $ml->id);
            }

            $gardien = $gardiens[rand(0, count($gardiens) - 1)]->id;
            $def = $defs[rand(0, count($defs) - 1)]->id;
            $index = rand(0, count($mls) - 1);
            $ml0 = $mlId[$index];
            unset($mlId[$index]); sort($mlId);
            $ml1 = $mlId[rand(0, count($mlId) - 1)];
            $atq = $atqs[rand(0, count($atqs) - 1)]->id;

            $titulaires = array(
                'T1' => $gardien,
                'T2' => $def,
                'T3' => $ml0,
                'T4' => $atq,
                'T5' => $ml1
            );
        }
        elseif($this->_organisation === "2-1-1"){
            $dfId = array();
            foreach ($defs as $df) {
                array_push($dfId, $df->id);
            }

            $gardien = $gardiens[rand(0, count($gardiens) - 1)]->id;
            $index = rand(0, count($defs) - 1);
            $def0 = $dfId[$index];
            unset($dfId[$index]); sort($dfId);
            $def1 = $dfId[rand(0, count($dfId) - 1)];
            $ml = $mls[rand(0, count($mls) - 1)]->id;
            $atq = $atqs[rand(0, count($atqs) - 1)]->id;

            $titulaires = array(
                'T1' => $gardien,
                'T2' => $def0,
                'T3' => $ml,
                'T4' => $atq,
                'T5' => $def1
            );
        }
        else{
            $atqId = array();
            foreach ($atqs as $atq) {
                array_push($atqId, $atq->id);
            }

            $gardien = $gardiens[rand(0, count($gardiens) - 1)]->id;
            $def = $defs[rand(0, count($defs) - 1)]->id;
            $ml = $mls[rand(0, count($mls) - 1)]->id;
            $index = rand(0, count($atqs) - 1);
            $atq0 = $atqId[$index];
            unset($atqId[$index]); sort($atqId);
            $atq1 = $atqId[rand(0, count($atqId) - 1)];

            $titulaires = array(
                'T1' => $gardien,
                'T2' => $def,
                'T3' => $ml,
                'T4' => $atq0,
                'T5' => $atq1
            );
        }

        foreach($titulaires as $titulaire){
            DB::table('joueurs')->where('id', $titulaire)->update([
                'sousContrat' => 1,
                'dureeContrat' => rand(2, 3),
                'salaire' => 20
            ]);
        }

        return $titulaires;
    }

    public function getId(){
        return $this->_id;
    }

    public function getIdClub()
    {
        return DB::table('equipes')->where('id', $this->_id)->value('idClub');
    }

    public function getNom()
    {
        return DB::table('clubs')->where('id', $this->getIdClub())->value('nom');
    }

    public function getBudget()
    {
        return DB::table('clubs')->where('id', $this->getIdClub())->value('budget');
    }

    public function updateBudget($soustraireBudget)
    {
        $currentBudget = $this->getBudget();

        $currentBudget = $currentBudget - $soustraireBudget;

        DB::table('clubs')->where('id', $this->getIdClub())->update([
          'budget' => $currentBudget
        ]);
    }

    public function ajoutBudget($ajouterBudget)
    {
      $currentBudget = $this->getBudget();

      $currentBudget = $currentBudget + $ajouterBudget;

      DB::table('clubs')->where('id', $this->getIdClub())->update([
        'budget' => $currentBudget
      ]);
    }

    public function getPoints()
    {
        return DB::table('clubs')->where('id', $this->getIdClub())->value('points');
    }

    public function updatePoints($pts)
    {
      $currentPoints = DB::table('clubs')->where('id', $this->getIdClub())->value('points');
      $currentPoints = $currentPoints + $pts;

      DB::table('clubs')->where('id', $this->getIdClub())->update([
        'points' => $currentPoints
      ]);
    }

    public function resetPoints()
    {
        DB::table('clubs')->where('id', $this->getIdClub())->update([
          'points' => 0
        ]);
    }

    public function getTitulaireInfos()
    {
        $titus = array();
        foreach ($this->getTitulaires() as $titu) {
            $nom = DB::table('joueurs')->where('id', $titu)->value('prenom');
            $nom .= ' ' . DB::table('joueurs')->where('id', $titu)->value('nom');

            $poste = DB::table('joueurs')->where('id', $titu)->value('poste');

            $age = DB::table('joueurs')->where('id', $titu)->value('age');

            $salaire = DB::table('joueurs')->where('id', $titu)->value('salaire');

            $dureeContrat = DB::table('joueurs')->where('id', $titu)->value('dureeContrat');

            $note = DB::table('joueurs')->where('id', $titu)->value('noteGlobale');

            //--------------------------------------
            array_push($titus, [
                'id' => $titu,
                'nom' => $nom,
                'poste' => $poste,
                'age' => $age,
                'salaire' => $salaire,
                'dureeContrat' => $dureeContrat,
                'note' => $note
            ]);
        }

        return $titus;
    }

    public function getRemplacantInfos()
    {
        $rempls = array();
        if($this->getRemplacants() != NULL) {
            foreach ($this->getRemplacants() as $rempl) {
                $nom = DB::table('joueurs')->where('id', $rempl)->value('prenom');
                $nom .= ' ' . DB::table('joueurs')->where('id', $rempl)->value('nom');

                $poste = DB::table('joueurs')->where('id', $rempl)->value('poste');

                $age = DB::table('joueurs')->where('id', $rempl)->value('age');

                $salaire = DB::table('joueurs')->where('id', $rempl)->value('salaire');

                $dureeContrat = DB::table('joueurs')->where('id', $rempl)->value('dureeContrat');

                $note = DB::table('joueurs')->where('id', $rempl)->value('noteGlobale');

                //--------------------------------------
                array_push($rempls, [
                    'id' => $rempl,
                    'nom' => $nom,
                    'poste' => $poste,
                    'age' => $age,
                    'salaire' => $salaire,
                    'dureeContrat' => $dureeContrat,
                    'note' => $note
                ]);
            }
        }
        $len = count($rempls);
        for ($i=0; $i < 3 - $len; $i++) {
            array_push($rempls, -1);
        }

        return $rempls;
    }

    public function getAutresInfos()
    {
        $autres = array();
        if($this->getAutres() != NULL) {
            foreach ($this->getAutres() as $autre) {
                $nom = DB::table('joueurs')->where('id', $autre)->value('prenom');
                $nom .= ' ' . DB::table('joueurs')->where('id', $autre)->value('nom');

                $poste = DB::table('joueurs')->where('id', $autre)->value('poste');

                $age = DB::table('joueurs')->where('id', $autre)->value('age');

                $salaire = DB::table('joueurs')->where('id', $autre)->value('salaire');

                $dureeContrat = DB::table('joueurs')->where('id', $autre)->value('dureeContrat');

                $note = DB::table('joueurs')->where('id', $autre)->value('noteGlobale');

                //--------------------------------------
                array_push($autres, [
                    'id' => $autre,
                    'nom' => $nom,
                    'poste' => $poste,
                    'age' => $age,
                    'salaire' => $salaire,
                    'dureeContrat' => $dureeContrat,
                    'note' => $note
                ]);
            }
        }
        else {
            $autres = -1;
        }

        return $autres;
    }

    public function setTitulaires($nouveauTitulaires)
    {
        DB::table('titulaires')->where('idEquipe', $this->_id)->update([
            'idT1' => $nouveauTitulaires[0],
            'idT2' => $nouveauTitulaires[1],
            'idT3' => $nouveauTitulaires[2],
            'idT4' => $nouveauTitulaires[3],
            'idT5' => $nouveauTitulaires[4]
        ]);

        foreach ($nouveauTitulaires as $titl) {
          DB::table('joueurs')->where('id', $titl)->update([
            'salaire' => 20,
          ]);
        }

        $this->_titulaires = $nouveauTitulaires;
    }

    public function getTitulaires()
    {
        return $this->_titulaires;
    }

    public function setRemplacants($nouveauRemplacants)
    {
        DB::table('remplacants')->where('idEquipe', $this->_id)->updateOrInsert(
            ['idEquipe' => $this->_id,],
            [
                'idR1' => $nouveauRemplacants[0],
                'idR2' => $nouveauRemplacants[1],
                'idR3' => $nouveauRemplacants[2]
            ]
        );
        foreach ($nouveauRemplacants as $rempls) {
          DB::table('joueurs')->where('id', $rempls)->update([
            'salaire' => 5,
            'dureeContrat' => 2,
            'sousContrat' => 1
          ]);
        }

        $this->_remplacants = $nouveauRemplacants;
    }

    public function getRemplacants()
    {
        return $this->_remplacants;
    }

    public function setAutres($nouveauJoueurs)
    {
        $autres = DB::table('effectif_autres')->select('idJoueur')->where('idEquipe', $this->_id)->get();
        foreach ($nouveauJoueurs as $key => $value) {
            if (property_exists($autres, $key)) {
                if ($autres[$key]->idJoueur != $value) {
                    DB::table('effectif_autres')->where('idEquipe', $this->_id)->update([
                        'idJoueur' => $value
                    ]);
                }
            }
            else {
                EffectifAutre::create([
                    'idEquipe' => $this->_id,
                    'idJoueur' => $value
                ]);
            }
        }

        foreach ($nouveauJoueurs as $jrs) {
          DB::table('joueurs')->where('id', $jrs)->update([
            'salaire' => 1,
            'sousContrat' => 1,
            'dureeContrat' =>2
          ]);
        }
    }

    public function getAutres()
    {
        return $this->_effectifAutres;
    }

    public function setOrganisation($organisation)
    {
        DB::table('equipes')->where('id', $this->_id)->update([
            'organisation' => $organisation
        ]);

        $this->_organisation = $organisation;
    }

    public function getOrganisation()
    {
        $formation = DB::table('equipes')->where('id', $this->_id)->value('organisation');
        return $formation;
    }

    public function setNotes()
    {
      $titulaires = DB::table('titulaires')->select('idT1', 'idT2', 'idT3', 'idT4', 'idT5')->where('idEquipe', $this->_id)->get();
      $remplacants = DB::table('remplacants')->select('idR1', 'idR2', 'idR3')->where('idEquipe', $this->_id)->get();
      $autres = DB::table('effectif_autres')->select('idJoueur')->where('idEquipe', $this->_id)->get();

      $notesTitulaires = array();

      array_push($notesTitulaires, DB::table('joueurs')->where('id', $titulaires[0]->idT1)->value('noteGlobale'));
      array_push($notesTitulaires, DB::table('joueurs')->where('id', $titulaires[0]->idT2)->value('noteGlobale'));
      array_push($notesTitulaires, DB::table('joueurs')->where('id', $titulaires[0]->idT3)->value('noteGlobale'));
      array_push($notesTitulaires, DB::table('joueurs')->where('id', $titulaires[0]->idT4)->value('noteGlobale'));
      array_push($notesTitulaires, DB::table('joueurs')->where('id', $titulaires[0]->idT5)->value('noteGlobale'));

      $notesAbsolue = $notesTitulaires;

      if(count($remplacants) != 0){
          array_push($notesAbsolue, DB::table('joueurs')->where('id', $remplacants[0]->idR1)->value('noteGlobale'));
          array_push($notesAbsolue, DB::table('joueurs')->where('id', $remplacants[0]->idR2)->value('noteGlobale'));
          array_push($notesAbsolue, DB::table('joueurs')->where('id', $remplacants[0]->idR3)->value('noteGlobale'));
      }

      for ($i=0; $i < count($autres); $i++) {
        array_push($notesAbsolue, DB::table('joueurs')->where('id', $autres[$i]->idJoueur)->value('noteGlobale'));
      }

      $noteAbsolue = floor(array_sum($notesAbsolue) / count($notesAbsolue));
      $notePartielle = floor(array_sum($notesTitulaires) / 5);

      $note = array(
        'absolue' => $noteAbsolue,
        'partielle' => $notePartielle,
      );

      //UPDATE in DB
      DB::table('equipes')->where('id', $this->_id)->update([
          'noteAbsolue' => $note['absolue'],
          'notePartielle' => $note['partielle']
      ]);

      $this->_notes = $note;
    }

    public function getNotes()
    {
      $noteAbs = DB::table('equipes')->where('id', $this->_id)->value('noteAbsolue');
      $noteParti = DB::table('equipes')->where('id', $this->_id)->value('notePartielle');

      $note = array(
        'absolue' => $noteAbs,
        'partielle' => $noteParti,
      );

      return $note;
    }

    public function echange($joueurA, $joueurB)
    {
        $newTitus = array();
        foreach ($this->_titulaires as $key => $value) {
            if ($value === $joueurA) {
                $this->_titulaires[$key] = $joueurB;
            }
            elseif($value === $joueurB) {
                $this->_titulaires[$key] = $joueurA;
            }
            array_push($newTitus, $this->_titulaires[$key]);
        }

        $newRempl = array();
        foreach ($this->_remplacants as $key => $value) {
            if ($value === $joueurA) {
                $this->_remplacants[$key] = $joueurB;
            }

            if ($value === $joueurB) {
                $this->_remplacants[$key] = $joueurA;
            }
            array_push($newRempl, $this->_remplacants[$key]);
        }
        foreach ($this->_effectifAutres as $key => $value) {
            if ($value === $joueurA) {
                $this->_effectifAutres[$key] = $joueurB;
            }

            if ($value === $joueurB) {
                $this->_effectifAutres[$key] = $joueurA;
            }
        }

        $this->setTitulaires($newTitus);
        $this->setRemplacants($newRempl);
        $this->deleteAutres();
        $this->setAutres($this->_effectifAutres);
    }

    public function deleteAutres()
    {
        DB::table('effectif_autres')->where('idEquipe', $this->_id)->delete();
    }

    public function insert()
    {
        $idClub = DB::table('clubs')->latest('id')->first()->id;

        Equipe::create([
          'idClub' => $idClub,
          'organisation' => $this->_organisation,
          'noteAbsolue' => 0,
          'notePartielle' => 0
        ]);

        Titulaire::create([
            'idEquipe' => $this->_id,
            'idT1' => $this->_titulaires['T1'],
            'idT2' => $this->_titulaires['T2'],
            'idT3' => $this->_titulaires['T3'],
            'idT4' => $this->_titulaires['T4'],
            'idT5' => $this->_titulaires['T5'],
        ]);
    }

    public function getSpectateurs()
    {
      $infos =  DB::table('clubs')->select('capaciteStade', 'idVille')->where('id', $this->getIdClub())->get();

      $capaStade = $infos[0]->capaciteStade;
      $attract =  DB::table('villes')->where('id', $infos[0]->idVille)->value('attractivite');

      return ($attract * $capaStade) / 100;
    }

    public function getSpectateursBDD($idMatch)
    {
      return DB::table('matches')->where('id', $idMatch)->value('nbSpectateurs');
    }

    public function getStade()
    {
      $idClub = $this->getIdClub();
      return DB::table('clubs')->where('id', $idClub)->value('stade');
    }

    public function newYear()
    {
        //update budget
        //update Note

        $idClub = $this->getIdClub();
        $budget = DB::table('clubs')->where('id', $idClub)->value('budget');

        $infoTitulaires = array();
        $infoRemplacants = array();
        $infoAutres = array();

        //recup les infos des titulaires, remplacants et autres

        foreach ($this->_titulaires as $key => $value) {
          $budget = $budget - 20;
          array_push($infoTitulaires, DB::table('joueurs')->select('age', 'dureeContrat', 'id')->where('id', $value)->get());
        }

        if (!empty($this->_remplacants)) {
            foreach ($this->_remplacants as $key => $value) {
              $budget = $budget - 5;
              array_push($infoRemplacants, DB::table('joueurs')->select('age', 'dureeContrat', 'id')->where('id', $value)->get());
            }
        }

        if (!empty($this->_effectifAutres)) {
            foreach ($this->_effectifAutres as $value) {
              $budget = $budget - 1;
              array_push($infoAutres, DB::table('joueurs')->select('age', 'dureeContrat', 'id')->where('id', $value)->get());
            }
        }

        //update le budget du club

        DB::table('clubs')->where('id', $idClub)->update([
            'budget' => $budget
        ]);

        //crée des tableaux tampons qui contiennent les id de l'effectif

        $nvTitu = array();
        foreach ($this->_titulaires as $key => $value) {
          array_push($nvTitu, $value);
        }

        if (!empty($this->_remplacants)) {
            $nvRempl = array();
            foreach ($this->_remplacants as $key => $value) {
              array_push($nvRempl, $value);
            }
        }

        $flagT = 0;
        $flagR = 0;

        $compteur = 0;

        //test si les joueurs de l'effectif ont plus de 40 ans ou que le contrat est terminé
        //supprime de la table 'joueurs' les joueurs qui ont plus de 40 ans (et de l'equipe)
        //supprime de l'equipe les joueurs qui sont arrivés au bout du contrat

        //titulaires
        foreach ($infoTitulaires as $titulaire) {
          if(($titulaire[0]->age > 40)){
            DB::table('joueurs')->where('id', $titulaire[0]->id)->delete();
            $nvTitu[$compteur] = 0;
            $flagT = 1;
          }

          if(($titulaire[0]->dureeContrat == 0) AND ($titulaire[0]->age <= 40)){
            $nvTitu[$compteur] = 0;
            DB::table('joueurs')->where('id', $titulaire[0]->id)->update([
                'sousContrat' => 0
            ]);
            $flagT = 1;
          }
          $compteur++;
        }
        $compteur = 0;

        //remplacants

        if (!empty($this->_remplacants)) {
            foreach ($infoRemplacants as $remplacant) {
              if(($remplacant[0]->age > 40)){
                DB::table('joueurs')->where('id', $remplacant[0]->id)->delete();
                $nvRempl[$compteur] = 0;
                $flagR = 1;
              }

              if(($remplacant[0]->dureeContrat == 0) AND ($remplacant[0]->age <= 40)){
                $nvRempl[$compteur] = 0;
                DB::table('joueurs')->where('id', $remplacant[0]->id)->update([
                    'sousContrat' => 0
                ]);
                $flagR = 1;
              }
              $compteur++;
            }
            $compteur = 0;
        }

        //effectif autres

        if (!empty($this->_effectifAutres)) {
            foreach ($infoAutres as $autre) {
              if(($autre[0]->age > 40)){
                DB::table('joueurs')->where('id', $autre[0]->id)->delete();
                DB::table('effectif_autres')->where('idJoueur', $autre-[0]>id)->delete();
                unset($this->_effectifAutres[$compteur]);
                sort($this->_effectifAutres);
              }

              if(($autre[0]->dureeContrat == 0) AND ($autre[0]->age <= 40)){
                DB::table('effectif_autres')->where('idJoueur', $autre[0]->id)->delete();
                unset($this->_effectifAutres[$compteur]);
                sort($this->_effectifAutres);
                DB::table('joueurs')->where('id', $autre[0]->id)->update([
                    'sousContrat' => 0
                ]);
              }
              $compteur++;
            }
            $compteur = 0;
        }

        //update les joueurs de l'equipe dans la BDD, l'id sera à 0 si le joueur est supprimé de l'équipe

        if($flagT == 1){
          DB::table('titulaires')->where('idEquipe', $this->_id)->update([
            'idT1' => $nvTitu[0],
            'idT2' => $nvTitu[1],
            'idT3' => $nvTitu[2],
            'idT4' => $nvTitu[3],
            'idT5' => $nvTitu[4]
          ]);
        }
        if (!empty($this->_remplacants)) {
            if($flagR == 1){
              DB::table('remplacants')->where('idEquipe', $this->_id)->update([
                'idR1' => $nvRempl[0],
                'idR2' => $nvRempl[1],
                'idR3' => $nvRempl[2]
              ]);
            }
        }

        //update des attributs d'instance

        $this->_titulaires = array(
            'T1' => $nvTitu[0],
            'T2' => $nvTitu[1],
            'T3' => $nvTitu[2],
            'T4' => $nvTitu[3],
            'T5' => $nvTitu[4]
        );

        if (!empty($this->_remplacants)) {
            $this->_remplacants = array(
              'R1' => $nvRempl[0],
              'R2' => $nvRempl[1],
              'R3' => $nvRempl[2]
            );
        }

        //update des points

        $this->resetPoints();
    }
}
