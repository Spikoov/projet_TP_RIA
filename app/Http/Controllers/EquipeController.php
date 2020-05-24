<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EquipeController extends Controller
{
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
