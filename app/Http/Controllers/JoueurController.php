<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JoueurController extends Controller
{
    private $_nom;
    private $_prenom;
    private $_age;
    private $_poste;
    private $_stats;
    private $_note;

    public function generateJoueur()
    {
        $listPrenom = array(
            'Philippe', 'Jean', 'Jérôme', 'Sébastien', 'Miguel', 'François', 'Loïc', 'Luca', 'Théo', 'Clément',
            'Victor', 'Stéphane', 'Giuseppe', 'Zelda', 'Thor', 'Tony', 'Lemmy', 'Francis', 'Thomas', 'Arnold',
            'Durotan', 'Loris', 'Anduin', 'Camille', 'Ragnar'
        );

        $listNom = array(
            'mar', 'chi', 'oni', 'flo', 'res', 'cor', 'reia', 'fig', 'loth', 'brok',
            'elli', 'ar', 'rein', 'hardt', 'hurl', 'enfer', 'cor', 'arch', 'son', 'sras',
            'moth', 'med', 'ori', 'ant', 'oni', 'het', 'field', 'chew', 'bacca', 'krank',
            'en', 'wag', 'kraft', 'kil', 'ami', 'kuru', 'oli', 'ken', 'obi', 'cci'
        );

        $listPostes = array(
            'gardien', 'defense', 'milieu', 'attaquant'
        )

        $this->_prenom = $this->generatePrenom($listPrenom);
        $this->_nom = $this->generateNom($listNom);
        $this->age = rand(17, 35);
        $this->_poste = $this->postes[array_rand($this->postes)]
        $this->_stats = $this->generateStats($this->_poste);
        $this->_note = $this->generateNotes();

        $this->insert();
    }

    public function generatePrenom($prenoms)
    {
        $length = count($prenoms) - 1;

        $randIndex = rand(0, $length);

        return $prenoms[$randIndex];
    }

    public function generateNom($noms)
    {
        $length = count($noms) - 1;
        $randNumber = rand(1, 4);
        $nomString = "";

        for ($i=0; $i < $randNumber; $i++) {
          $randNom = rand(0, $length);
          $nomString .= $noms[$randNom];
        }
        return ucfirst($nomString);
    }

    public function generateStats($position)
    {
        if ($position === 'gardien') {
            $gk0 = 70; $df0 = 40; $ml0 = 0; $at0 = 0;
            $gk1 = 100; $df1 = 80; $ml1 = 20; $at1 = 20;
        }
        elseif ($position === 'defense') {
            $gk0 = 0; $df0 = 70; $ml0 = 20; $at0 = 0;
            $gk1 = 10; $df1 = 100; $ml1 = 60; $at1 = 20;.
        }
        elseif ($position === 'milieu') {
            $gk0 = 0; $df0 = 20; $ml0 = 70; $at0 = 20;
            $gk1 = 10; $df1 = 60; $ml1 = 100; $at1 = 60;
        }
        else {
            $gk0 = 0; $df0 = 0; $ml0 = 20; $at0 = 70;
            $gk1 = 10; $df1 = 20; $ml1 = 60; $at1 = 100;
        }

        $stats = array(
            'tir' => rand($at0, $at1),
            'passe' => rand($ml0, $ml1),
            'technique' => rand(40, 100),
            'placement' => rand(40, 100),
            'vitesse' => rand(40, 100),
            'tacle' => rand($df0, $df1),
            'arret' => rand($gk0, $gk1),
            'forme' => 100,
            'endurance' => rand(60, 100),
        );

        return $stats;
    }

    public function generateNotes()
    {
        $notes = array(
            'gardien' => floor(array_sum($this->_stats['placement'], $this->_stats['arret'], $this->_stats['arret'], $this->_stats['tacle'])/4),
            'defense' => floor(array_sum($this->_stats['placement'], $this->_stats['tacle'], $this->_stats['tacle'], $this->_stats['passe'])/4),
            'milieu' => floor(array_sum($this->_stats['passe'], $this->_stats['technique'], $this->_stats['vitesse'], $this->_stats['placement'])/4),
            'attaque' => floor(array_sum($this->_stats['tir'], $this->_stats['tir'], $this->_stats['vitesse'], $this->_stats['technique'])/4),
        );

        return $notes
    }

    public function insert()
    {
        DB::table('joueurs')->truncate();

        //insert
    }
}
