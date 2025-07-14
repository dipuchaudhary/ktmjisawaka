<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MuddaReportController extends Controller
{
    public function overallStatus()
    {
        return view('frontend.MuddaReport.index');
    }

}
