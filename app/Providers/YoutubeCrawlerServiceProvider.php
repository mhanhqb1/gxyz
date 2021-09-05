<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Providers\CommonServiceProvider;
use App\Models\Post;
use App\Models\PostTag;

class YoutubeCrawlerServiceProvider extends ServiceProvider
{
    public static $youtubeApi = 'https://www.googleapis.com/youtube/v3/';

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public static function getApiKey() {
        return config('services.google')['youtube_api_key'];
    }

    /*
     * Get data by source key
     */
    public static function getDataBySourceKey($source, $data = [], $nextToken = Null, $skip = False) {
        # Init
        $keyword = urlencode($source->source_params);
        $sourceId = $source->id;
        $sortType = 'date';
        if (empty($source->crawl_at)) {
            $sortType = 'viewCount';
        }
        $apiKey = self::getApiKey();
        $apiUrl = self::$youtubeApi."search?part=snippet,id&q={$keyword}&key={$apiKey}&order={$sortType}&maxResults=50";
        if (!empty($nextToken)) {
            $apiUrl .= "&pageToken={$nextToken}";
        }

        $res = CommonServiceProvider::call_api($apiUrl);
        if (!empty($res['items'])) {
            foreach ($res['items'] as $v) {
                if ($v['id']['kind'] == 'youtube#video') {
                    $data[] = self::getVideoItem($v, $sourceId);
                }
            }
            if (!empty($res['nextPageToken']) && $skip == False && empty($source->crawl_at)) {
                $data = self::getDataBySourceKey($source, $data, $res['nextPageToken']);
            }
        }

        return $data;
    }

    /*
     * Get data by source key
     */
    public static function getDataByPlaylist($source, $data = [], $nextToken = Null, $skip = False) {
        # Init
        $playlistId = urlencode($source->source_params);
        $sourceId = $source->id;
        $sortType = 'date';
        if (empty($source->crawl_at)) {
            $sortType = 'viewCount';
        }
        $apiKey = self::getApiKey();
        $apiUrl = self::$youtubeApi."playlistItems?part=snippet,id&playlistId={$playlistId}&key={$apiKey}&order={$sortType}&maxResults=50";
        if (!empty($nextToken)) {
            $apiUrl .= "&pageToken={$nextToken}";
        }

        $res = CommonServiceProvider::call_api($apiUrl);
        if (!empty($res['items'])) {
            foreach ($res['items'] as $v) {
                $data[] = self::getVideoItem($v, $sourceId, true);
            }
            if (!empty($res['nextPageToken']) && $skip == False && empty($source->crawl_at)) {
                $data = self::getDataBySourceKey($source, $data, $res['nextPageToken']);
            }
        }

        return $data;
    }

    // Get Video Item
    public static function getVideoItem($item, $sourceId, $isPlaylist) {
        $snippet = $item['snippet'];
        if (!empty($isPlaylist)) {
            $youtubeId = $snippet['resourceId']['videoId'];
        } else {
            $youtubeId = $item['id']['videoId'];
        }
        return [
            'title' => $snippet['title'],
            'slug' => CommonServiceProvider::convertURL($snippet['title']),
            'description' => $snippet['description'],
            'image' => $snippet['thumbnails']['high']['url'],
            'tags' => '',
            'type' => 1,
            'source_type' => Post::$sourceType['youtube'],
            'source_url' => 'https://www.youtube.com/watch?v='.$youtubeId,
            'source_id' => $youtubeId,
            'master_source_id' => $sourceId
        ];
    }

    // Update video detail
    public static function updateVideoDetail($post) {
        # Init
        $apiKey = self::getApiKey();
        $today = date('Y-m-d');
        echo $post->id.' - '.$post->title.PHP_EOL;
        $apiUrl = self::$youtubeApi."videos?part=snippet,contentDetails,statistics&id={$post->source_id}&key={$apiKey}";
        $res = CommonServiceProvider::call_api($apiUrl);
        if (!empty($res['error']['code']) && $res['error']['code'] == 403) {
            return false;
        }
        if (!empty($res['items'])) {
            foreach ($res['items'] as $v) {
                if ($v['kind'] == 'youtube#video') {
                    $snippet = $v['snippet'];
                    if (!empty($snippet['liveBroadcastContent']) && $snippet['liveBroadcastContent'] != 'none') {
                        continue;
                    }
                    $ageRestricted = !empty($v['contentDetails']['contentRating']['ytRating']) ? 1 : 0;
                    if (!empty($ageRestricted)) {
                        $post->status = -1;
                    } else {
                        $tags = !empty($snippet['tags']) ? $snippet['tags'] : '';
                        if (!empty($tags)) {
                            $post->tags = implode(',', $tags);
                            foreach ($tags as $t) {
                                $_pt = PostTag::where('name', $t)->first();
                                if (!empty($_pt)) {
                                    $_pt->count = $_pt->count + 1;
                                } else {
                                    $_pt = new PostTag();
                                    $_pt->name = $t;
                                    $_pt->slug = CommonServiceProvider::convertURL($t);
                                    $_pt->status = 1;
                                }
                                $_pt->save();
                            }
                        }
                    }
                    $post->crawl_at = $today;
                    $post->save();
                }
            }
        } elseif (isset($res['items'])) {
            $post->status = -1;
            $post->crawl_at = $today;
            $post->save();
        }
        return True;
    }
}
