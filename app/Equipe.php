<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
  protected $fillable = [
    'idClub', 'organisation', 'noteAbsolue', 'notePartielle'
  ];
}
