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
                    if (isset($autre[$i])) {
                        array_push($_effectifAutres, $autres[$i]->idJoueur);
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
        $this->_organisation = "1-2-1";
        $this->_titulaires = $this->generateTitulaires();

        $this->insert();
        $this->setNotes();
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


        $gardien = $gardiens[rand(0, count($gardiens) - 1)]->id;
        $def = $defs[rand(0, count($defs) - 1)]->id;
        $ml0 = $mls[rand(0, count($mls) - 1)]->id;
        $ml1 = $mls[rand(0, count($mls) - 1)]->id;
        $atq = $atqs[rand(0, count($atqs) - 1)]->id;

        $titulaires = array(
            'T1' => $gardien,
            'T2' => $def,
            'T3' => $ml0,
            'T4' => $atq,
            'T5' => $ml1
        );

        foreach($titulaires as $titulaire){
            DB::table('joueurs')->where('id', $titulaire)->update([
                'sousContrat' => 1,
                'dureeContrat' => rand(1, 3),
                'salaire' => 20
            ]);
        }

        return $titulaires;
    }

    public function getId(){
        return $this->_id;
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

        $this->_titulaires = $nouveauTitulaires;
    }

    public function getTitulaires()
    {
        return $this->_titulaires;
    }

    public function setRemplacants($nouveauRemplacants)
    {
        DB::table('remplacants')->where('idEquipe', $this->_id)->update([
            'idR1' => $nouveauRemplacants[0],
            'idR2' => $nouveauRemplacants[1],
            'idR3' => $nouveauRemplacants[2]
        ]);

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
        foreach ($this->_titulaires as $key => $value) {
            if ($value === $joueurA['id']) {
                $value = $joueurB['id'];
            }
            elseif($value === $joueurB['id']) {
                $value = $joueurA['id'];
            }
        }

        foreach ($this->_remplacants as $key => $value) {
            if ($value === $joueurA['id']) {
                $value = $joueurB['id'];
            }

            if ($value === $joueurB['id']) {
                $value = $joueurA['id'];
            }
        }

        foreach ($this->$_effectifAutres as $key => $value) {
            if ($value === $joueurA['id']) {
                $value = $joueurB['id'];
            }

            if ($value === $joueurB['id']) {
                $value = $joueurA['id'];
            }
        }

        $this->setTitulaires($this->_titulaires);
        $this->setRemplacants($this->_remplacants);
        $this->setAutres($this->_effectifAutres);

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

    public function newYear()
    {
        //update budget
        //update Note


    }
}
