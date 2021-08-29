<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Abraham\TwitterOAuth\TwitterOAuth;
use SebastianBergmann\CodeCoverage\Report\PHP;

class Post extends Model {

    protected $table = "posts";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'image',
        'description',
        'tags',
        'is_hot',
        'is_18',
        'type', // 1: video, 0: image
        'status',
        'source_type',
        'source_url',
        'source_id',
        'stream_url',
        'crawl_at',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    public $timestamps = true;

    public static $youtubeApi = 'https://www.googleapis.com/youtube/v3/';

    public static function get_list($params) {
        # Init
        $limit = !empty($params['limit']) ? $params['limit'] : 16;
        $page = !empty($params['page']) ? $params['page'] : 1;
        $offset = ($page - 1)*$limit;

        # Get data
        if (!empty($params['is_random'])) {
            $data = self::inRandomOrder();
        } else {
            $data = self::orderBy('id', 'desc');
        }

        # Filter
        if (isset($params['status']) && $params['status'] != '') {
            $data = $data->where('status', $params['status']);
        }
        if (isset($params['is_hot']) && $params['is_hot'] != '') {
            $data = $data->where('is_hot', $params['is_hot']);
        }
        if (isset($params['is_18']) && $params['is_18'] != '') {
            $data = $data->where('is_18', $params['is_18']);
        }

        # Return data
        $data = $data->offset($offset)->limit($limit)->get();
        return $data;
    }

    public static function addUpdateMulti($data) {
        foreach ($data as $v) {
            echo $v['title'].PHP_EOL;
            Post::updateOrCreate([
                'source_type' => $v['source_type'],
                'source_id' => $v['source_id']
            ], $v);
        }
    }

    public static function dailyCrawler() {
        # Init
        $today = date('Y-m-d', time());
        $limit = 5;

        # Get list ID
        $sources = MasterSource::where(function($query) use ($today){
                $query->where('crawl_at', null);
                $query->orWhere('crawl_at', '<', $today);
            })
            ->where('loop', MasterSource::$loop['daily'])
            ->where('status', 1)
            ->limit($limit)
            ->get();
        if (!$sources->isEmpty()) {
            foreach ($sources as $s) {
                if ($s->source_type == MasterSource::$sourceType['youtube_key']) {
                    $data = self::getDataBySourceKey($s);
                    echo count($data).PHP_EOL;
                    self::addUpdateMulti($data);
                }
                $s->crawl_at = $today;
                $s->save();
            }
        }
    }

    /*
     * Youtube channel crawler
     */
    public static function getDataBySourceKey($source, $data = [], $nextToken = Null, $skip = False) {
        # Init
        $keyword = urlencode($source->source_params);
        $sourceId = $source->id;
        $sortType = 'date';
        if (empty($source->crawl_at)) {
            $sortType = 'viewCount';
        }
        $today = date('Y-m-d', time());
        $apiKey = config('services.google')['youtube_api_key'];
        $apiUrl = self::$youtubeApi."search?part=snippet,id&q={$keyword}&key={$apiKey}&order={$sortType}&maxResults=50";
        if (!empty($nextToken)) {
            $apiUrl .= "&pageToken={$nextToken}";
        }

        $res = self::call_api($apiUrl);
        if (!empty($res['items'])) {
            foreach ($res['items'] as $v) {
                if ($v['id']['kind'] == 'youtube#video') {
                    $snippet = $v['snippet'];
                    $youtubeId = $v['id']['videoId'];
                    $tmp = [
                        'title' => $snippet['title'],
                        'slug' => self::convertURL($snippet['title']),
                        'description' => $snippet['description'],
                        'image' => $snippet['thumbnails']['high']['url'],
                        'tags' => '',
                        'type' => 1,
                        'source_type' => 'youtube',
                        'source_url' => 'https://www.youtube.com/watch?v='.$youtubeId,
                        'source_id' => $youtubeId,
                        'master_source_id' => $sourceId
                    ];
                    $data[] = $tmp;
                }
            }
            if (!empty($res['nextPageToken']) && $skip == False && empty($source->crawl_at)) {
                $data = self::getDataBySourceKey($source, $data, $res['nextPageToken']);
            }
        }

        return $data;
    }

    /*
     * Call Api
     */
    protected static function call_api($url) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    /**
     * Convert string to url
     *
     * @author thailh
     * @param string $string String for convert
     * @return string
     */
    public static function convertURL($str, $delimiter = '-')
    {
        $str = preg_replace("/(\,|-|\.|\'|\+)/", '', $str);
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = preg_replace('/\s+/', '-', $str);
        $str = str_replace("/", "-", $str);
        $str = str_replace(" ", "-", $str);
        $str = str_replace("?", "", $str);
        $str = str_replace("#", "", $str);
        // replace non letter or digits by divider
        $str = preg_replace('~[^\pL\d]+~u', $delimiter, $str);
        // remove unwanted characters
        $str = preg_replace('~[^-\w]+~', '', $str);
        // trim
        $str = trim($str, $delimiter);
        $str = preg_replace('~-+~', $delimiter, $str);

        return strtolower($str);
    }

}
