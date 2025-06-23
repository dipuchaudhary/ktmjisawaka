<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DartaController extends Controller
{
    public function index()
    {
        return view('frontend.darta');
    }
}
