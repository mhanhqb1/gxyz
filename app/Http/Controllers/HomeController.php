<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Idol;
use App\Models\YoutubeChannel;
use App\Models\YoutubeChannelVideo;
use App\Models\Video;
use App\Models\Post;

class HomeController extends Controller {

    /**
     * Homepage
     */
    public static function index() {
        $images = Image::get_list([
            'status' => 1,
            'is_18' => 1,
            'limit' => 16,
        ]);
        $limit = 8;
        $offset = 0;
        $videos = Post::where('status', 1)->where('type', 1)->where('is_18', 0)->orderBy('id', 'desc')->limit($limit)->offset($offset)->get();
        $video18 = Post::where('status', 1)->where('type', 1)->where('is_18', 1)->orderBy('id', 'desc')->limit($limit)->offset($offset)->get();
        return view('home.new_index', ['idols' => $images, 'videos' => $videos, 'video18' => $video18]);
    }

    /**
     * Get video stream
     */
    public static function getVideoStream(Request $request) {
        # Init
        $result = array(
            'status' => 'OK',
            'data' => ''
        );
        $videoExpired = 5*60*60;//fix tam
        $apiGetStream = "http://45.76.207.18:5000/stream/";
        $params = $request->all();
        $videoId = !empty($request->video_id) ? $request->video_id : '';
        $video = Post::find($videoId);
        if (!empty($video)) {
            if ($video->source_type == 'twitter') {
                $result['data'] = $video->stream_url;
            } elseif (!empty($video->stream_url) && (strtotime($video->crawl_at) + $videoExpired) > time()) {
                $result['data'] = $video->stream_url;
            } else {
                $apiGetStream = $apiGetStream . $video->source_id . '/';
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $apiGetStream,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                if (strpos($response, "googlevideo.com") !== false) {
                    $result['data'] = $response;
                    $video->stream_url = $response;
                    $video->crawl_at = time();
                    $video->save();
                } else {
                    $result['status'] = 'ERROR';
                }
            }
        }
        echo json_encode($result);
        die();
    }

    /**
     * Get list images
     */
    public static function images(Request $request) {
        $params = $request->all();
        if (empty($params['page'])) {
            $params['page'] = 1;
        }
        $limit = 16;
        $offset = ($params['page'] - 1)*$limit;
        $images = Image::limit($limit)->offset($offset)->orderBy('id', 'desc')->get();
        $pageTitle = 'Hot Girl Images - Page ' . $params['page'];
        return view('home.new_image', ['images' => $images, 'pageTitle' => $pageTitle, 'params' => $params]);
    }

    /**
     * Get list videos
     */
    public static function videos(Request $request) {
        $params = $request->all();
        if (empty($params['page'])) {
            $params['page'] = 1;
        }
        $limit = 16;
        $offset = ($params['page'] - 1)*$limit;
        $data = Post::where('type', 1)->where('status', 1)->where('is_18', 0)->orderBy('id', 'desc')->limit($limit)->offset($offset)->get();
        $pageTitle = 'Hot Girl Videos - Page '.$params['page'];
        $route = 'home.videos';
        return view('home.new_video', ['route' => $route, 'data' => $data, 'pageTitle' => $pageTitle, 'params' => $params]);
    }

    /**
     * Get list videos
     */
    public static function videos18(Request $request) {
        $params = $request->all();
        if (empty($params['page'])) {
            $params['page'] = 1;
        }
        $limit = 16;
        $offset = ($params['page'] - 1)*$limit;
        $data = Post::where('status', 1)->where('type', 1)->where('is_18', 1)->orderBy('id', 'desc')->limit($limit)->offset($offset)->get();
        $pageTitle = 'Sexy Girl Videos - Page '.$params['page'];
        $route = 'home.18videos';
        return view('home.new_video', ['route' => $route, 'data' => $data, 'pageTitle' => $pageTitle, 'params' => $params]);
    }

    /**
     * Get image detail
     */
    public static function imageDetail($id) {
        $pageTitle = 'SBGC - Images ' . $id;
        $image = Image::find($id);
        $limit = 50;
        if (empty($image)) {
            $images = Image::inRandomOrder()->where('status', 1)->where('is_hot', 1)->limit($limit)->get();
            return view('home.new_image', ['images' => $images, 'pageTitle' => $pageTitle]);
        }
        $pageImage = $image->url;
        $related = Image::inRandomOrder()->where('status', 1)->where('id', '!=', $id);
        if (!empty($image->model_id)) {
            $related = $related->where('model_id', $image->model_id);
        } else {
            $related = $related->limit($limit);
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
    public static function idolDetail($id) {
        $pageTitle = 'SBGC - Idol ' . $id;
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
    public static function videoDetail($slug, $id) {
        $pageTitle = 'Sexy Girl Video ' . $id;
        $video = Post::find($id);
        $limit = 16;
        if (empty($video)) {
            $data = Post::inRandomOrder()->where('type', 1)->where('status', 1)->limit($limit);
            return view('home.new_video', ['data' => $data, 'pageTitle' => $pageTitle]);
        }
        $pageTitle = 'Sexy Girl Video - ' . $video->title;
        $pageImage = $video->image;
        $related = Post::inRandomOrder()->where('status', 1)->where('type', 1)->limit($limit)->get();
        return view('home.new_video_detail', ['related' => $related,'video' => $video, 'pageTitle' => $pageTitle, 'id' => $id, 'pageImage' => $pageImage]);
    }

    /**
     * Get list 18 +images
     */
    public static function images18(Request $request) {
        $params = $request->all();
        if (empty($params['page'])) {
            $params['page'] = 1;
        }
        $limit = 16;
        $offset = ($params['page'] - 1)*$limit;
        $images = Image::where('is_18', 1)->limit($limit)->offset($offset)->orderBy('id', 'desc')->get();
        $pageTitle = 'Sexy Girl Videos - Page ' . $params['page'];
        return view('home.new_image', ['images' => $images, 'pageTitle' => $pageTitle, 'params' => $params]);
    }

    /**
     * flickrCrawler
     */
    public static function flickrCrawler() {
        set_time_limit(0);
        Image::flickr_firt_crawler();
        die('1');
    }

    /**
     * flickrCrawler
     */
    public static function flickrDailyCrawler() {
        set_time_limit(0);
        Image::flickr_daily_crawler();
        die('1');
    }

    /**
     * Youtube crawler
     */
    public static function youtubeCrawler() {
        set_time_limit(0);
        Post::dailyCrawler();
        die();
        // YoutubeChannel::youtube_channel_crawler(5);
        // YoutubeChannel::youtube_playlist_crawler(5);
        die('1');
    }

    /**
     * Youtube crawler
     */
    public static function twitterCrawler() {
        set_time_limit(0);
        Video::twitter_crawler();
        // YoutubeChannel::youtube_channel_crawler(5);
        // YoutubeChannel::youtube_playlist_crawler(5);
        die('1');
    }

}
