<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = [
      'nom', 'idVille', 'stade', 'capaciteStade', 'budget', 'points'
    ];
}
