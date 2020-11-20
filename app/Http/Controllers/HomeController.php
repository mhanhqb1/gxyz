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
//        Image::flickr_firt_crawler();
        $images = Image::get_list([
            'status' => 0
        ]);
        return view('home.index', ['images' => $images]);
    }
    
    /**
     * flickrCrawler
     */
    public static function flickrCrawler()
    {
        Image::flickr_firt_crawler();
        die('1');
    }
}