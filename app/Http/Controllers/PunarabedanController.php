<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PunarabedanController extends Controller
{
    public function index() {
        return view('frontend.punarabedan.edit');
    }

    public function edit($id){

    }
}
