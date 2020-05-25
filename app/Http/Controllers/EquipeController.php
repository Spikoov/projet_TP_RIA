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
<<<<<<< HEAD
        $this->_titulaires = generateTitulaires();
        $this->_organisation = setOrganisation("1-2-1");
        $_notes = setNotes();
=======
        $this->_organisation = $this->setOrganisation("1-2-1");
        $this->_titulaires = $this->generateTitulaires();
        $_notes = $this->setNotes();
>>>>>>> 55f126ccb4c22caba93488f3ae3a09a4ea94d7d5

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
    }

    public function getId(){
        return $this->_id;
    }

    public function setTitulaires($titulaires, $nouveauTitulaires)
    {
        
    }

    public function getTitulaires()
    {
        // J
    }

    public function setRemplacants($remplacants, $nouveauRemplacants)
    {
        // J
    }

    public function getRemplacants()
    {
        // J
    }

    public function setAutres($joueurs, $nouveauJoueurs)
    {
        // J
    }

    public function getAutres()
    {
        // J
    }

    public function setOrganisation($organisation)
    {

      return $organisation;
    }

    public function getOrganisation($idEquipe)
    {
        $formation = DB::table('equipes')->where('id', $idEquipe)->value('organisation');
        return $formation->organisation;
    }

    public function setNotes()
    {
        // M
    }

    public function getNotes()
    {
        $noteAbsolue = DB::table('equipes')->where('id', $idEquipe)->value('organisation');
    }

    public function insert()
    {
        // M
    }
}
