<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Titulaire extends Model
{
  protected $fillable = [
    'idEquipe', 'idT1', 'idT2', 'idT3', 'idT4', 'idT5'
  ];
}
