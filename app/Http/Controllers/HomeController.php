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
use App\Models\PostImage;
use PhpParser\Node\Expr\PostInc;

class HomeController extends Controller {

    /**
     * Homepage
     */
    public static function index() {
        $limit = 8;
        $offset = 0;
        $images = Post::where('status', 1)->where('type', 0)->orderBy('id', 'desc')->limit($limit*2)->offset($offset)->get();
        $videos = Post::where('status', 1)->where('type', 1)->where('is_18', 0)->orderBy('id', 'desc')->limit($limit*2)->offset($offset)->get();
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
        $videoExpired = 2*60*60;//fix tam
        $apiGetStream = "http://45.76.207.18:5000/stream/";
        $params = $request->all();
        $videoId = !empty($request->video_id) ? $request->video_id : '';
        $video = Post::find($videoId);
        if (!empty($video)) {
            if (in_array($video->source_type, array('twitter', 'imgccc'))) {
                $result['data'] = $video->stream_url;
                if ($video->source_type == 'imgccc') {
                    $result['source'] = 'iframe';
                }
            } elseif (!empty($video->stream_url) && ($video->stream_crawl + $videoExpired) > time()) {
                $result['data'] = $video->stream_url;
                $result['aaa'] = 1;
            } else {
                $apiGetStream = $apiGetStream . $video->source_id;
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
                    $video->stream_crawl = time();
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
        $images = Post::where('type', 0)->where('status', 1)->limit($limit)->offset($offset)->orderBy('id', 'desc')->get();
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

    public static function imageView(Request $request) {
        $img = !empty($request->img) ? $request->img : '';
        if (empty($img)) {
            return redirect(route('home.index'));
        }
        return view('home.image_view', ['img' => $img]);
    }

    /**
     * Get video detail
     */
    public static function postDetail($slug, $id) {
        $post = Post::find($id);
        $limit = 16;
        if (empty($post)) {
            return redirect(route('home.index'));
        }
        $pageTitle = 'Sexy Girl - ' . $post->title;
        $pageImage = $post->image;
        $related = Post::inRandomOrder()->where('id', '!=', $id)->where('status', 1)->where('type', 0);
        if (!empty($post->is_18)) {
            $related = $related->where('is_18', 1);
        }
        $related = $related->limit($limit)->get();
        $postImages = PostImage::where('post_id', $id)->pluck('image');
        return view('home.post_detail', ['postImages' => $postImages, 'related' => $related,'post' => $post, 'pageTitle' => $pageTitle, 'id' => $id, 'pageImage' => $pageImage]);
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
        $related = Post::inRandomOrder()->where('status', 1)->where('type', 1);
        if (!empty($video->is_18)) {
            $related = $related->where('is_18', 1);
        }
        $related = $related->limit($limit)->get();
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
