<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\YoutubeChannel;
use App\Models\YoutubeChannelVideo;

class HomeController extends Controller
{
    /**
     * Homepage
     */
    public static function index()
    {
        $images = Image::get_list([
            'status' => 1,
            'is_hot' => 1,
            'limit' => 16,
            'is_random' => 1
        ]);
        $videos = YoutubeChannelVideo::get_list([
            'status' => 1,
            'is_hot' => 1,
            'limit' => 16
        ]);
        return view('home.index', ['images' => $images, 'videos' => $videos]);
    }
    
    /**
     * Get list images
     */
    public static function images(Request $request)
    {
        $params = $request->all();
        if (empty($params['page'])) {
            $params['page'] = 1;
        }
        $limit = 16;
        $images = Image::inRandomOrder()->where('status', 1)->where('is_hot', 1)->limit($limit)->get();
        $pageTitle = 'Sexy Girl - '.$params['page'];
        return view('home.image', ['images' => $images, 'pageTitle' => $pageTitle, 'params' => $params]);
    }
    
    /**
     * Get list videos
     */
    public static function videos(Request $request)
    {
        $params = $request->all();
        if (empty($params['page'])) {
            $params['page'] = 1;
        }
        $limit = 16;
        $data = YoutubeChannelVideo::orderBy('is_hot', 'desc')->orderBy('id', 'desc')->where('status', 1)->paginate($limit);
        $pageTitle = 'SBGC - Total Videos';
        return view('home.video', ['data' => $data, 'pageTitle' => $pageTitle, 'params' => $params]);
    }
    
    /**
     * Get image detail
     */
    public static function imageDetail($id)
    {
        $pageTitle = 'SBGC - Images '.$id;
        $image = Image::find($id);
        if (empty($image)) {
            $images = Image::orderBy('is_hot', 'desc')->orderBy('id', 'desc')->where('status', 1)->paginate($limit);
            return view('home.image', ['images' => $images, 'pageTitle' => $pageTitle]);
        }
        $pageImage = $image->url;
        return view('home.image_detail', ['image' => $image, 'pageTitle' => $pageTitle, 'id' => $id, 'pageImage' => $pageImage]);
    }
    
    /**
     * Get video detail
     */
    public static function videoDetail($id)
    {
        $pageTitle = 'SBGC - Video '.$id;
        $video = YoutubeChannelVideo::find($id);
        if (empty($video)) {
            $data = YoutubeChannelVideo::orderBy('is_hot', 'desc')->orderBy('id', 'desc')->where('status', 1)->paginate($limit);
            return view('home.video', ['data' => $data, 'pageTitle' => $pageTitle]);
        }
        $pageTitle = 'SBGC - '.$video->title;
        $pageImage = $video->image;
        return view('home.video_detail', ['video' => $video, 'pageTitle' => $pageTitle, 'id' => $id, 'pageImage' => $pageImage]);
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
    /**
     * Youtube crawler
     */
    public static function youtubeCrawler()
    {
        set_time_limit(0);
        YoutubeChannel::youtube_channel_crawler(5);
        YoutubeChannel::youtube_playlist_crawler(5);
        die('1');
    }
}