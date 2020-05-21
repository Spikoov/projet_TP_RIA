<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoadController extends Controller
{
    private $prenom = array(
        'Philippe',
        'Jean',
    );

    public function welcome()
    {


        return view('welcome');
    }

    public function generatePrenom()
    {
        $length = count($this->$prenom) - 1;

        $randIndex = rand(0, $length);

        return $this->$prenom[$randIndex];
    }

    public function generateNom()
    {
        $length = count($this->$nom) - 1;
        $randNumber = rand(1, 4);
        $nomString = "";

        for ($i=0; $i < $randNumber; $i++) {
          $randNom = rand(0, $length);
          $nomString .= $this->$nom[$randNumber];
        }
        return ucfirst($nomString);
    }

    public function stats()
    {
        // code...
    }

    public function insert()
    {
        // code...
    }
}
