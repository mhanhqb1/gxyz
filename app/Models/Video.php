<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'image',
        'stream_url',
        'source_id',
        'crawl_at',
        'is_hot',
        'status',
        'is_18'
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
    
    public static function video_crawler(){
        $data = self::get_channel_videos();
        if (!empty($data)) {
            foreach ($data as $v) {
                self::updateOrCreate([
                    'source_id' => $v['source_id']
                ], $v);
            }
        }
    }
    
    /*
     * Youtube channel crawler
     */
    public static function get_channel_videos($data = [], $nextToken = Null, $skip = False) {
        # Init
        $keyword = 'korean%20bj';
        $today = date('Y-m-d', time());
        $apiKey = config('services.google')['youtube_api_key'];
        $apiUrl = self::$youtubeApi."search?part=snippet,id&q={$keyword}&key={$apiKey}&order=date&maxResults=50";
        if (!empty($nextToken)) {
            $apiUrl .= "&pageToken={$nextToken}";
        }
        
        $res = self::call_api($apiUrl);
        if (!empty($res['items'])) {
            foreach ($res['items'] as $v) {
                if ($v['id']['kind'] == 'youtube#video') {
                    $snippet = $v['snippet'];
                    $data[] = [
                        'source_id' => $v['id']['videoId'],
                        'title' => $snippet['title'],
                        'description' => $snippet['description'],
                        'image' => $snippet['thumbnails']['high']['url'],
                        'status' => 1
                    ];
                }
                
            }
            if (!empty($res['nextPageToken']) && $skip == False) {
                $data = self::get_channel_videos($data, $res['nextPageToken']);
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

}
