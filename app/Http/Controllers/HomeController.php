<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Idol;
use App\Models\YoutubeChannel;
use App\Models\YoutubeChannelVideo;
use App\Models\Video;

class HomeController extends Controller
{
    /**
     * Homepage
     */
    public static function index()
    {
        $idols = Idol::get_list([
            'status' => 1,
            'is_hot' => 1,
            'limit' => 16,
            'is_random' => 1
        ]);
        $videos = Video::get_list([
            'status' => 1,
            'is_random' => 1
        ]);
        return view('home.index', ['idols' => $idols, 'videos' => $videos]);
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
        $pageTitle = 'Sexy Girl Collection - '.$params['page'];
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
        $data = Video::inRandomOrder()->where('status', 1)->paginate($limit);
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
        $limit = 50;
        if (empty($image)) {
            $images = Image::inRandomOrder()->where('status', 1)->where('is_hot', 1)->limit($limit)->get();
            return view('home.image', ['images' => $images, 'pageTitle' => $pageTitle]);
        }
        $pageImage = $image->url;
        $related = Image::inRandomOrder()->where('status', 1)->where('id', '!=', $id);
        if (!empty($image->model_id)) {
            $related = $related->where('model_id', $image->model_id);
        }
        $related = $related->get();
        
        return view('home.image_detail', [
            'image' => $image, 
            'pageTitle' => $pageTitle, 
            'id' => $id, 
            'pageImage' => $pageImage,
            'related' => $related
        ]);
    }
    
    /**
     * Get image detail
     */
    public static function idolDetail($id)
    {
        $pageTitle = 'SBGC - Idol '.$id;
        $idol = Idol::find($id);
        $pageImage = $idol->image;
        $related = Image::inRandomOrder()->where('model_id', $id)->where('status', 1)->get();
        $relatedIdols = Idol::inRandomOrder()->where('id', '!=', $id)->limit(4)->get();
        
        return view('home.idol_detail', [
            'idol' => $idol, 
            'pageTitle' => $pageTitle, 
            'id' => $id, 
            'pageImage' => $pageImage,
            'related' => $related,
            'relatedIdols' => $relatedIdols
        ]);
    }
    
    /**
     * Get video detail
     */
    public static function videoDetail($id)
    {
        $pageTitle = 'SBGC - Video '.$id;
        $video = Video::find($id);
        if (empty($video)) {
            $data = Video::inRandomOrder()->where('status', 1)->paginate($limit);
            return view('home.video', ['data' => $data, 'pageTitle' => $pageTitle]);
        }
        $pageTitle = 'SBGC - '.$video->title;
        $pageImage = $video->image;
        return view('home.video_detail', ['video' => $video, 'pageTitle' => $pageTitle, 'id' => $id, 'pageImage' => $pageImage]);
    }
    
    /**
     * Get list 18 +images
     */
    public static function images18(Request $request)
    {
        $params = $request->all();
        if (empty($params['page'])) {
            $params['page'] = 1;
        }
        $limit = 16;
        $images = Image::inRandomOrder()->where('status', 1)->where('is_hot', 1)->limit($limit)->get();
        $pageTitle = 'Sexy Girl Collection 18+ - '.$params['page'];
        return view('home.image', ['images' => $images, 'pageTitle' => $pageTitle, 'params' => $params]);
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
        Video::video_crawler();
        YoutubeChannel::youtube_channel_crawler(5);
        YoutubeChannel::youtube_playlist_crawler(5);
        die('1');
    }
}