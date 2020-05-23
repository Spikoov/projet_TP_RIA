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
        for ($i=0; $i < 200; $i++) {
            (new JoueurController)->generateJoueur();
        }

        return view('welcome');
    }
}
