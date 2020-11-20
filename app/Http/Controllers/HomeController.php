<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Homepage
     */
    public static function index()
    {
        return view('home.index');
    }
}