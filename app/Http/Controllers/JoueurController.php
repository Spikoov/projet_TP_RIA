<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JoueurController extends Controller
{
    private $prenom = array(
        'Philippe', 'Jean', 'Jérôme', 'Sébastien', 'Miguel', 'François', 'Loïc', 'Luca', 'Théo', 'Clément',
        'Victor', 'Stéphane', 'Giuseppe', 'Zelda', 'Thor', 'Tony', 'Lemmy', 'Francis', 'Thomas', 'Arnold',
        'Durotan', 'Loris', 'Anduin', 'Camille', 'Ragnar'
    );

    private $nom = array(
        'mar', 'chi', 'oni', 'flo', 'res', 'cor', 'reia', 'fig', 'loth', 'brok',
        'elli', 'ar', 'rein', 'hardt', 'hurl', 'enfer', 'cor', 'arch', 'son', 'sras',
        'moth', 'med', 'ori', 'ant', 'oni', 'het', 'field', 'chew', 'bacca', 'krank',
        'en', 'wag', 'kraft', 'kil', 'ami', 'kuru', 'oli', 'ken', 'obi', 'cci'
    );

    public function generateJoueur($value='')
    {
        for ($i=0; $i < 200; $i++) {
            $nPrenom = $this->generatePrenom();
            $nNom = $this->generateNom();
            $nstats = $this->generateStats();

            $this->insert();
        }
    }

    public function generatePrenom()
    {
        $length = count($this->prenom) - 1;

        $randIndex = rand(0, $length);

        return $this->prenom[$randIndex];
    }

    public function generateNom()
    {
        $length = count($this->nom) - 1;
        $randNumber = rand(1, 4);
        $nomString = "";

        for ($i=0; $i < $randNumber; $i++) {
          $randNom = rand(0, $length);
          $nomString .= $this->nom[$randNom];
        }
        return ucfirst($nomString);
    }

    public function generateStats()
    {
        // code...
    }

    public function insert()
    {
        // code...
    }
}
