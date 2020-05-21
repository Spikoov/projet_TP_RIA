<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\JoueurController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EquipeController;


class LoadController extends Controller
{
    public function welcome()
    {
        (new JoueurController)->generateJoueur();

        return view('welcome');
    }
}
