<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remplacant extends Model
{
  protected $fillable = [
    'idEquipe', 'idR1', 'idR2', 'idR3'
  ];
}
