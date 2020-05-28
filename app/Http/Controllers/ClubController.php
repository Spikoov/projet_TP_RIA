<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ville;
use App\Club;
use Illuminate\Support\Facades\DB;

class ClubController extends Controller
{
    private $_nomClub;
    private $_ville;
    private $_stade;
    private $_budget;
    private $_points;

    public function generateClub()
    {
        $this->_ville = $this->generateVille();
        $this->_nomClub = $this->generateNomClub();
        $this->_stade = $this->generateStade();
        $this->_budget = 200;
        $this->_points = 0;

        $this->insert();
    }

    public function generateVille()
    {
        $villes = array(
            'Bastia', 'Corte', 'Ajaccio', 'Calvi', 'Porto-Vecchio', 'Propriano', 'Conca', 'Paris', 'Marseille', 'Berlin',
            'Lyon', 'Avignon', 'Rennes', 'Madrid', 'Hambourg', 'Milan', 'Tatooine', 'Orgrimmar', 'Hurlevent', 'St-Etienne',
            'Avalon', 'Nantes', 'Kaamelott', 'Poudlard', 'Manchester', 'Gotham', 'Cyrnea', 'Minas Tirith', 'Minas Morgul', 'Erebor'
        );

        $ville = array(
            'nom' => $villes[array_rand($villes)],
            'attractivite' => rand(50, 100)
        );

        return $ville;
    }

    public function generateNomClub()
    {
        $clubs = array(
            'AC ', 'SC ', 'FC ', 'Real d', 'AS ', 'Inter ', '', ' City', 'Olympique d', ' United'
        );

        $club = $clubs[array_rand($clubs)];
        if ($club === ' City' XOR $club === ' United') {
            $club = $this->_ville['nom'] . $club;
        }
        else {
            if ($club === 'Real d' XOR $club === 'Olympique d') {
                $firstLetter = substr($this->_ville['nom'], 0, 1);
                if($firstLetter === 'A' OR $firstLetter === 'I' OR $firstLetter === 'U'
                OR $firstLetter === 'E' OR $firstLetter === 'O' OR $firstLetter === 'Y')
                    $club .= '\'';
                elseif ($this->_ville === 'Cyrnea') {
                    $club .= 'u ';
                }
                else
                    $club .= 'e ';
            }
            $club .= $this->_ville['nom'];
        }
        return $club;
    }

    public function generateStade()
    {
        $nom = 'Stade d';
        $firstLetter = substr($this->_nomClub, 0, 1);

        if($firstLetter === 'A' OR $firstLetter === 'I' OR $firstLetter === 'U'
        OR $firstLetter === 'E' OR $firstLetter === 'O' OR $firstLetter === 'Y')
            $nom .= 'e l\'';
        elseif (substr($this->_nomClub, 0, 4) === 'Real')
            $nom .= 'u ';
        else
            $nom .= 'e ';

        $nom .= $this->_nomClub;

        $stade = array(
            'nom' => $nom,
            'capacite' => rand(10000, 25000)
        );

        return $stade;
    }

    public function insert()
    {
        Ville::create([
          'nom' => $this->_ville['nom'],
          'attractivite' => $this->_ville['attractivite']
        ]);

        $idVille = DB::table('villes')->latest('id')->first()->id;

        Club::create([
          'nom' => $this->_nomClub,
          'idVille' => $idVille,
          'stade' => $this->_stade['nom'],
          'capaciteStade' => $this->_stade['capacite'],
          'budget' => $this->_budget,
          'points' => $this->_points
      ]);
    }
}
