<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoadController extends Controller
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

    public function welcome()
    {
        var_dump($this->nom);

        return view('welcome');
    }

    public function generatePrenom()
    {
        // code...
    }

    public function generateNom()
    {
        // code...
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
