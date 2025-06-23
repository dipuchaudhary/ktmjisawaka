<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DartaController extends Controller
{
    public function create()
    {
        return view('frontend.mudda_darta.create');
    }
}
