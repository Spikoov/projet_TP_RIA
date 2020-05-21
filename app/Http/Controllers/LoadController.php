<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoadController extends Controller
{
    private $prenom = array('' => , );

    public function generate()
    {
        

        return view('welcome');
    }
}
