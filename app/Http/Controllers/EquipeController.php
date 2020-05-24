<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\JoueurController;
use App\Http\Controllers\ClubController;

class EquipeController extends Controller
{
    private $_titulaires;
    private $_remplaçants;
    private $_autres;
    private $_organisation;
    private $_notes;

    public function generateEquipe()
    {
        $this->_titulaires = getTitulaires();

        $this->insert();
    }

    public function getTitulaires()
    {
        // code...
    }

    public function insert()
    {
        //insert...
    }
}
