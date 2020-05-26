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

    public function __construct()
    {
        //$this->_id = $lastId + 1;
    }

    public function generateEquipe()
    {
        $this->_organisation = "1-2-1";
        $this->_titulaires = $this->generateTitulaires();
        $_notes = $this->setNotes();

        $this->insert();
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
            'gardien' => $gardien,
            'defense' => $def,
            'milieu1' => $ml0,
            'milieu2' => $ml1,
            'attaque' => $atq,
        );

        foreach($titulaires as $titulaire){
            DB::table('joueurs')->where('id', $titulaire)->update([
                'sousContrat' => 1,
                'dureeContrat' => rand(1, 3),
                'salaire' => 20
            ]);
        }
    }

    public function getId(){
        return $this->_id;
    }

    public function setTitulaires($nouveauTitulaires)
    {
        DB::table('titulaires')->where('id', $this->_id)->update([
            'idT1' => $nouveauTitulaires[0],
            'idT2' => $nouveauTitulaires[1],
            'idT3' => $nouveauTitulaires[2],
            'idT4' => $nouveauTitulaires[3],
            'idT5' => $nouveauTitulaires[4],
        ]);

        $this->_titulaires = $nouveauTitulaires;
    }

    public function getTitulaires()
    {
        // J
    }

    public function setRemplacants($nouveauRemplacants)
    {
        // J
    }

    public function getRemplacants()
    {
        // J
    }

    public function setAutres($nouveauJoueurs)
    {
        // J
    }

    public function getAutres()
    {
        // J
    }

    public function setOrganisation($organisation)
    {
        DB::table('equipes')->where('id', $this->_id)->update([
            'organisation' => $organisation;
        ]);

        $this->_organisation = $organisation;
    }

    public function getOrganisation()
    {
        $formation = DB::table('equipes')->where('id', $this->_id)->value('organisation');
        return $formation->organisation;
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

      $notePartielle = $noteTitulaire1->noteGlobale + $noteTitulaire2->noteGlobale + $noteTitulaire3->noteGlobale + $noteTitulaire4->noteGlobale + $noteTitulaire5->noteGlobale;

      $noteRemplacant1 = DB::table('joueurs')->where('id', $remplacants[0]->idR1)->value('noteGlobale');
      $noteRemplacant2 = DB::table('joueurs')->where('id', $remplacants[0]->idR2)->value('noteGlobale');
      $noteRemplacant3 = DB::table('joueurs')->where('id', $remplacants[0]->idR3)->value('noteGlobale');

      for ($i=0; $i < count($autres); $i++) {
        $noteEffectif
      }

      $note = array(
        'absolue' => $noteAbsolue,
        'partielle' => $notePartielle,
      );

      return $note;
    }

    public function getNotes()
    {
      $noteAbs = DB::table('equipes')->where('id', $this->_id)->value('noteAbsolue');
      $noteParti = DB::table('equipes')->where('id', $this->_id)->value('notePartielle');

      $note = array(
        'absolue' => $noteAbs->noteAbsolue,
        'partielle' => $noteParti->notePartielle,
      );

      return $note;
    }

    public function insert()
    {
        $idClub = DB::table('clubs')->latest('id')->first()->id;

        Equipe::create([
          'idClub' => $idClub,
          'organisation' => $this->_organisation,
          'noteAbsolue' => $this->_notes['absolue'],
          'notePartielle' => $this->_notes['partielle'],
        ]);
    }
}
