<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Providers\CommonServiceProvider;

use App\Models\Post;
use App\Models\PostTag;

class TwitterServiceProvider extends ServiceProvider
{
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

    public static function getTwitterAuth()
    {
        $consumer_key = config('app.twitter_api_key');
        $consumer_secret = config('app.twitter_api_secret_key');
        $access_token = config('app.twitter_access_token');
        $access_token_secret = config('app.twitter_access_token_secret');
        return new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
    }

    // Get item detail
    public static function addTweet($item, $source) {
        if (!empty($item->extended_entities->media)) {
            $_id = $item->id;
            foreach ($item->extended_entities->media as $k => $media) {
                $data = [
                    'title' => 'Image '.$_id,
                    'slug' => 'post-'.$_id,
                    'description' => '',
                    'image' => $media->media_url_https,
                    'tags' => $source->custom_tags,
                    'type' => 0,
                    'source_type' => Post::$sourceType['twitter'],
                    'source_url' => 'https://twitter.com/SofiaVergara/status/'.$_id,
                    'source_id' => $k.' - '.$_id,
                    'master_source_id' => $source->id
                ];
                if (!empty($media->video_info)) {
                    $data['type'] = 1;
                    $data['title'] = 'Video '.$_id;
                    foreach ($media->video_info->variants as $_v) {
                        if ($_v->content_type == 'video/mp4') {
                            $data['stream_url'] = $_v->url;
                            break;
                        }
                    }
                }
                echo $data['title'].PHP_EOL;
                Post::updateOrCreate([
                    'source_type' => $data['source_type'],
                    'source_id' => $data['source_id']
                ], $data);
                if (!empty($source->custom_tags)) {
                    PostTag::addUpdateTags($source->custom_tags, $data['type']);
                }
            }
        }
    }

    // Get user timeline
    public static function getUserTimeline($source)
    {
        $twId = $source->source_params;
        $twitter = self::getTwitterAuth();
        $since_id = Post::where('source_type', Post::$sourceType['twitter'])->where('master_source_id', $source->id)->max('slug');
        if (!empty($since_id)) {
            $since_id = str_replace('post-', '', $since_id);
        } else {
            $since_id = 1;
        }
        // echo $since_id; die();
        $content = $twitter->get("statuses/user_timeline", [
            "screen_name" => $twId,
            "since_id" => $since_id,
            "count" => 200,
            'tweet_mode' => 'extended'
        ]);
        foreach ($content as $item) {
            self::addTweet($item, $source);
        }
    }
}
