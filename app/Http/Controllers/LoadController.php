<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoadController extends Controller
{
    private $prenom = array(
        'Philippe',
        'Jean',
    );

    public function welcome()
    {


        return view('welcome');
    }

    public function generatePrenom()
    {
        // code...
    }

    public function generateNom()
    {
        // code...
    }

    public function stats()
    {
        // code...
    }

    public function insert()
    {
        // code...
    }
}
