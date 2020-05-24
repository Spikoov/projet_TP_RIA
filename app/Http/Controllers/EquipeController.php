<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EquipeController extends Controller
{

<<<<<<< HEAD
  public function generateEquipe($value='')
  {

  }
=======
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
>>>>>>> acbcf19eaa2ecef867a25ac96fd40660517e03f0
}
