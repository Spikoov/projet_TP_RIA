<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Joueur extends Model
{
    protected $fillable = [
        'prenom', 'nom', 'age', 'poste', 'tir', 'passe', 'technique', 'placement', 'vitesse', 'tacle',
        'arret', 'forme', 'endurance', 'noteGlobale', 'sousContrat', 'dureeContrat', 'salaire'
    ];
}
