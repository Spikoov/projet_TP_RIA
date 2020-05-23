<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            'Avalon', 'Nantes', 'Kaamelott', 'Poudlard', 'Tokyo', 'Gotham', 'Cyrnea', 'Minas Tirith', 'Minas Morgul', 'Erebor'
        );

        $ville = array(
            'nom' => $villes[array_rand($villes)],
            'attractivite' => rand(0, 100)
        );

        return $ville;
    }

    public function generateNomClub()
    {
        $clubs = array(
            'AC ', 'SC ', 'FC ', 'Real d', 'AS ', 'Inter ', '', ' City', 'CS ', ' United'
        );

        $club = $clubs[array_rand($clubs)];
        if ($club === ' City' XOR $club === ' United') {
            $club = $this->_ville['nom'] . $club;
        }
        else {
            $firstLetter = substr($this->_ville['nom'], 0, 1);
            if ($club === 'Real d') {
                if($firstLetter === 'A' OR $firstLetter === 'I' OR $firstLetter === 'U'
                OR $firstLetter === 'A' OR $firstLetter === 'O' OR $firstLetter === 'Y')
                    $club .= '\'';
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

        if($firstLetter === 'A' OR $firstLetter === 'I' OR $firstLetter === 'U')
            $nom .= 'e l\'';
        elseif ($firstLetter === 'R')
            $nom .= 'u ';
        else
            $nom .= 'e ';

        $nom .= $this->_nomClub;

        $stade = array(
            'nom' => $nom,
            'capacite' => rand(5000, 15000)
        );

        return $stade;
    }

    public function insert()
    {
        //DB::table('villes')->truncate();
        //DB::table('clubs')->truncate();

        //insert villes...
        //insert clubs...
    }
}
