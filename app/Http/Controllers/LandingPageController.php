<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function showLandingPage()
    {
        return view('landing');
    }
}