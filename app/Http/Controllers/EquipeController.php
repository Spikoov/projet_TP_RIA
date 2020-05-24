<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipe;
use App\Titulaire;
use App\Remplacant;
use App\EffectifAutre;

class EquipeController extends Controller
{
    private $_titulaires;
    private $_remplacants;
    private $_effectifAutres;
    private $_organisation;
    private $_notes;

    public function generateEquipe()
    {
        $this->_titulaires = generateTitulaires();
        $this->_organisation = setOrganisation();
        $_notes = setNotes();

        $this->insert();
    }

    public function generateTitulaires()
    {
        // J
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
