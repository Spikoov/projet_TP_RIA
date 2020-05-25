<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EffectifAutre extends Model
{
  protected $fillable = [
    'idEquipe', 'idJoueur'
  ];
}
