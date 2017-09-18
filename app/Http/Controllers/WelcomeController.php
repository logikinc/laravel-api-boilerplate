<?php

namespace App\Http\Controllers;

use App\Http\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('app');
    }
}
