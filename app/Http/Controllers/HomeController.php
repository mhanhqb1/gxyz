<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Idol;
use App\Models\YoutubeChannel;
use App\Models\YoutubeChannelVideo;
use App\Models\Video;

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
        $limit = 16;
        $offset = 0;
        $videos = Video::where('status', 1)->where('is_18', 1)->orderBy('id', 'desc')->limit($limit)->offset($offset)->get();
        return view('home.new_index', ['idols' => $images, 'videos' => $videos]);
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
        $videoExpired = time() + 5*60*60;//fix tam
        $apiGetStream = "https://floating-everglades-87112.herokuapp.com/";
        $params = $request->all();
        $videoId = !empty($request->video_id) ? $request->video_id : '';
        $video = Video::find($videoId);
        if (!empty($video)) {
            if (!empty($video->stream_url) && ($video->crawl_at + $videoExpired) > time()) {
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
        $data = Video::inRandomOrder()->where('status', 1)->where('is_18', 0)->paginate($limit);
        $pageTitle = 'Hot Girl Videos - Page '.$params['page'];
        return view('home.new_video', ['data' => $data, 'pageTitle' => $pageTitle, 'params' => $params]);
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
        $data = Video::where('status', 1)->where('is_18', 1)->orderBy('id', 'desc')->limit($limit)->offset($offset)->get();
        $pageTitle = 'Sexy Girl Videos - Page '.$params['page'];
        return view('home.new_video', ['data' => $data, 'pageTitle' => $pageTitle, 'params' => $params]);
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
    public static function videoDetail($id) {
        $pageTitle = 'SBGC - Video ' . $id;
        $video = Video::find($id);
        if (empty($video)) {
            $data = Video::inRandomOrder()->where('status', 1)->paginate($limit);
            return view('home.video', ['data' => $data, 'pageTitle' => $pageTitle]);
        }
        $pageTitle = 'SBGC - ' . $video->title;
        $pageImage = $video->image;
        return view('home.video_detail', ['video' => $video, 'pageTitle' => $pageTitle, 'id' => $id, 'pageImage' => $pageImage]);
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
        Video::video_crawler();
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
