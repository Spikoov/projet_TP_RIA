<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
  protected $fillable = [
    'saison', 'idClub1', 'idClub2', 'stade', 'scoreClub1', 'scoreClub2', 'nbSpectateurs'
  ];
}
