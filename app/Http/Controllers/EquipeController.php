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
    private $_titulaires;
    private $_remplacants;
    private $_effectifAutres;
    private $_organisation;
    private $_allOrganisations = array('1-2-1', '2-1-1', '1-1-2');
    private $_notes;

    public function generateEquipe()
    {
        $this->_organisation = $this->setOrganisation($this->_allOrganisations[array_rand($this->_allOrganisations)]);
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

        $gardien = $gardiens[rand($gardiens)]->id;

        $titulaires = array(
            'gardien' => $gardien
        );

        //$positions = explode("-", $this->_organisation);
        $positions = array('1', '2', '1');

        //def
        for ($i=0; $i < (int)$positions[0]; $i++) {
            $def = $defs[array_rand($defs)]->id;
            $titulaires['defense'] = $def;
        }
        //ml
        for ($i=0; $i < (int)$positions[1]; $i++) {

        }
        //atq
        for ($i=0; $i < (int)$positions[2]; $i++) {

        }

        print_r($titulaires);
    }

    public function setTitulaires($titulaires, $nouveauTitulaires)
    {
        // J
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
        // M
    }

    public function getOrganisation()
    {
        // M
    }

    public function setNotes()
    {
        // M
    }

    public function getNotes()
    {
        // M
    }

    public function insert()
    {
        // M
    }
}
