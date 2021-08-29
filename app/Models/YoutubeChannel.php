<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterSource;
use App\Models\YoutubeChannelVideo;

class YoutubeChannel extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'youtube_id',
        'title',
        'description',
        'published_at',
        'image',
        'total_video',
        'total_view',
        'total_comment',
        'total_subscriber',
        'is_hidden_subscriber',
        'crawl_at',
        'cate_id',
        'master_source_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    public $timestamps = true;

    public static $youtubeApi = 'https://www.googleapis.com/youtube/v3/';

    /*
     * Youtube channel crawler
     */
    public static function youtube_channel_crawler($limit = null){
        # Init
        $today = date('Y-m-d', time());

        # Get list ID
        $channelIds = MasterSource::where(function($query) use ($today){
            $query->where('crawl_at', null);
            $query->orWhere('crawl_at', '<', $today);
        })->where('type', MasterSource::$type['video'])
                ->where('source_type', MasterSource::$sourceType['youtube']);
        if (!empty($limit)) {
            $channelIds = $channelIds->limit($limit);
        }
        $channelIds = $channelIds->get();
        if (!$channelIds->isEmpty()) {
            foreach ($channelIds as $c) {
                $cYoutubeId = $c->source_params;
                # Get channel info
                $infos = self::get_channel_info($cYoutubeId);
                if (!empty($infos)) {
                    $self = self::updateOrCreate([
                        'youtube_id' => $cYoutubeId
                    ], $infos);

                    # Get channel videos
                    $videos = self::get_channel_videos($self);
                    foreach ($videos as $video) {
                        YoutubeChannelVideo::updateOrCreate(['youtube_id' => $video['youtube_id']], $video);
                    }
                }

                # Update flag
                $c->crawl_at = $today;
                $c->save();
            }
        }
    }

    /*
     * Youtube playlist crawler
     */
    public static function youtube_playlist_crawler($limit = null){
        # Init
        $today = date('Y-m-d', time());

        # Get list ID
        $channelIds = MasterSource::where(function($query) use ($today){
            $query->where('crawl_at', null);
            $query->orWhere('crawl_at', '<', $today);
        })->where('type', MasterSource::$type['video'])
                ->where('source_type', MasterSource::$sourceType['youtube_playlist']);
        if (!empty($limit)) {
            $channelIds = $channelIds->limit($limit);
        }
        $channelIds = $channelIds->get();
        if (!$channelIds->isEmpty()) {
            foreach ($channelIds as $c) {
                $playlistId = $c->source_params;
                # Get channel videos
                $videos = self::get_playlist_videos($playlistId);
                foreach ($videos as $video) {
                    YoutubeChannelVideo::updateOrCreate(['youtube_id' => $video['youtube_id']], $video);
                }
                # Update flag
                $c->crawl_at = $today;
                $c->save();
            }
        }
    }

    /*
     * Get channel info
     */
    public static function get_channel_info($channelId) {
        # Init
        $data = [];
        $apiKey = config('services.google')['youtube_api_key'];
        $part = 'snippet,statistics,brandingSettings';
        $apiUrl = self::$youtubeApi."channels?part={$part}&id={$channelId}&key={$apiKey}";

        $res = self::call_api($apiUrl);
        if (!empty($res['items'])) {
            $snippet = $res['items'][0]['snippet'];
            $statistics = $res['items'][0]['statistics'];
            $data = [
                'youtube_id' => $channelId,
                'title' => $snippet['title'],
                'description' => $snippet['description'],
                'published_at' => !empty($snippet['publishedAt']) ? date('Y-m-d H:i:s', strtotime($snippet['publishedAt'])) : null,
                'image' => !empty($snippet['thumbnails']['high']['url']) ? $snippet['thumbnails']['high']['url'] : '',
                'total_view' => !empty($statistics['viewCount']) ? $statistics['viewCount'] : 0,
                'total_comment' => !empty($statistics['commentCount']) ? $statistics['commentCount'] : 0,
                'total_subscriber' => !empty($statistics['subscriberCount']) ? $statistics['subscriberCount'] : 0,
                'total_video' => !empty($statistics['videoCount']) ? $statistics['videoCount'] : 0,
                'is_hidden_subscriber' => !empty($statistics['hiddenSubscriberCount']) ? 1 : 0,
                'related_channels' => !empty($res['items'][0]['brandingSettings']['channel']['featuredChannelsUrls']) ? $res['items'][0]['brandingSettings']['channel']['featuredChannelsUrls'] : [],
            ];
        }

        return $data;
    }

    /*
     * Get playlist videos
     */
    public static function get_playlist_videos($playlistId, $data = [], $nextToken = Null) {
        # Init
        $apiKey = config('services.google')['youtube_api_key'];
        $apiUrl = self::$youtubeApi."playlistItems?part=snippet,id&playlistId={$playlistId}&key={$apiKey}&order=date&maxResults=50";
        if (!empty($nextToken)) {
            $apiUrl .= "&pageToken={$nextToken}";
        }

        $res = self::call_api($apiUrl);
        if (!empty($res['items'])) {
            foreach ($res['items'] as $v) {
                $snippet = $v['snippet'];
                $data[] = [
                    'youtube_channel_id' => $snippet['channelId'],
                    'youtube_id' => $snippet['resourceId']['videoId'],
                    'title' => $snippet['title'],
                    'description' => $snippet['description'],
                    'published_at' => date('Y-m-d H:i:s', strtotime($snippet['publishedAt'])),
                    'image' => $snippet['thumbnails']['high']['url']
                ];
            }
            if (!empty($res['nextPageToken'])) {
                $data = self::get_playlist_videos($playlistId, $data, $res['nextPageToken']);
            }
        }

        return $data;
    }

    /*
     * Get channel videos
     */
    public static function get_channel_videos($channel, $data = [], $nextToken = Null) {
        # Init
        $channelId = $channel->youtube_id;
        $apiKey = config('services.google')['youtube_api_key'];
        $apiUrl = self::$youtubeApi."search?part=snippet,id&channelId={$channelId}&key={$apiKey}&order=date&maxResults=50";
        if (!empty($nextToken)) {
            $apiUrl .= "&pageToken={$nextToken}";
        }

        $res = self::call_api($apiUrl);
        if (!empty($res['items'])) {
            foreach ($res['items'] as $v) {
                if ($v['id']['kind'] == 'youtube#video') {
                    $snippet = $v['snippet'];
                    $data[] = [
                        'sp_youtube_id' => $channel->id,
                        'youtube_channel_id' => $channelId,
                        'youtube_id' => $v['id']['videoId'],
                        'title' => $snippet['title'],
                        'description' => $snippet['description'],
                        'published_at' => date('Y-m-d H:i:s', strtotime($snippet['publishedAt'])),
                        'image' => $snippet['thumbnails']['high']['url']
                    ];
                }

            }
            if (!empty($res['nextPageToken'])) {
                $data = self::get_channel_videos($channel, $data, $res['nextPageToken']);
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
