<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;

class HomeController extends Controller
{
    /**
     * Homepage
     */
    public static function index()
    {
        $images = Image::get_list([
            'status' => 1
        ]);
        return view('home.index', ['images' => $images]);
    }
}