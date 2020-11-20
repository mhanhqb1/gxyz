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
            'status' => 0,
            'is_hot' => 1
        ]);
        return view('home.index', ['images' => $images]);
    }
    
    /**
     * Get list images
     */
    public static function images()
    {
        $images = Image::get_list([
            'status' => 0
        ]);
        return view('home.image', ['images' => $images]);
    }
    
    /**
     * Get list 18 +images
     */
    public static function images18()
    {
        $images = Image::get_list([
            'status' => 0,
            'is_18' => 1
        ]);
        return view('home.image', ['images' => $images]);
    }
    
    /**
     * flickrCrawler
     */
    public static function flickrCrawler()
    {
        set_time_limit(0);
        Image::flickr_firt_crawler();
        die('1');
    }
}