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
            'status' => 1,
            'is_hot' => 1
        ]);
        return view('home.index', ['images' => $images]);
    }
    
    /**
     * Get list images
     */
    public static function images()
    {
        $limit = 16;
        $images = Image::orderBy('id', 'desc')->where('status', 1)->paginate($limit);
        $pageTitle = 'SBGC - Total Images';
        return view('home.image', ['images' => $images, 'pageTitle' => $pageTitle]);
    }
    
    /**
     * Get list 18 +images
     */
    public static function images18()
    {
        $limit = 16;
        $images = Image::orderBy('id', 'desc')->where('status', 1)->where('is_18', 1)->paginate($limit);
        $pageTitle = 'SBGC - 18+ Images';
        return view('home.image', ['images' => $images, 'pageTitle' => $pageTitle]);
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
    /**
     * flickrCrawler
     */
    public static function flickrDailyCrawler()
    {
        set_time_limit(0);
        Image::flickr_daily_crawler();
        die('1');
    }
}