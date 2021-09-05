<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Abraham\TwitterOAuth\TwitterOAuth;
use SebastianBergmann\CodeCoverage\Report\PHP;

use App\Providers\YoutubeCrawlerServiceProvider;
use App\Providers\TwitterServiceProvider;
use App\Providers\CommonServiceProvider;

use App\Models\PostTag;

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
        'stream_crawl',
        'master_source_id'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    public $timestamps = true;

    public static $sourceType = [
        'youtube' => 'youtube',
        'twitter' => 'twitter',
        'xiuren' => 'xiuren'
    ];

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
        if (isset($params['type']) && $params['type'] != '') {
            $data = $data->where('type', $params['type']);
        }
        if (!empty($params['source_type'])) {
            $data = $data->where('source_type', $params['source_type']);
        }
        if (!empty($params['master_source_id'])) {
            $data = $data->where('master_source_id', $params['master_source_id']);
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
                    $data = YoutubeCrawlerServiceProvider::getDataBySourceKey($s);
                    echo count($data).PHP_EOL;
                    self::addUpdateMulti($data);
                } elseif ($s->source_type == MasterSource::$sourceType['youtube_playlist']) {
                    $data = YoutubeCrawlerServiceProvider::getDataByPlaylist($s);
                    echo count($data).PHP_EOL;
                    self::addUpdateMulti($data);
                } elseif ($s->source_type == MasterSource::$sourceType['youtube']) {
                    $data = YoutubeCrawlerServiceProvider::getDataByChannel($s);
                    echo count($data).PHP_EOL;
                    self::addUpdateMulti($data);
                } elseif ($s->source_type == MasterSource::$sourceType['twitter']) {
                    TwitterServiceProvider::getUserTimeline($s);
                }
                $s->crawl_at = $today;
                $s->save();
            }
        }
    }

    // Update video detail
    public static function Youtube_updateVideoDetail() {
        $posts = Post::where('type',1)
            // ->where('status', 1)
            ->where('source_type', Post::$sourceType['youtube'])
            ->where('crawl_at', null)
            ->limit(100)
            ->get();
        if (!$posts->isEmpty()) {
            foreach ($posts as $post) {
                if (!YoutubeCrawlerServiceProvider::updateVideoDetail($post)) {
                    break;
                }
            }
        }
    }

    public static function autoPublishPosts($limit) {
        $limit = rand(1, $limit);
        echo $limit;
        // Youtube
        $posts = Post::inRandomOrder()
            ->where('type',1)
            ->where('status', 0)
            ->where('source_type', self::$sourceType['youtube'])
            ->where('crawl_at', '!=', null)
            ->limit($limit)
            ->get();
        if (!$posts->isEmpty()) {
            foreach ($posts as $k => $p) {
                echo $k.' - '.$p->title.PHP_EOL;
                $p->status = 1;
                $p->created_at = date('Y-m-d H:i:s');
                $p->save();
            }
        }

        // Xiuren
        $posts = Post::inRandomOrder()
            ->where('type', 0)
            ->where('status', -3)
            ->where('source_type', self::$sourceType['xiuren'])
            ->where('crawl_at', '!=', null)
            ->limit($limit)
            ->get();
        if (!$posts->isEmpty()) {
            foreach ($posts as $k => $p) {
                echo $k.' - '.$p->title.PHP_EOL;
                $tags = explode(',', $p->tags);
                foreach ($tags as $t) {
                    $_pt = PostTag::where('name', trim($t))->first();
                    if (!empty($_pt)) {
                        $_pt->count = $_pt->count + 1;
                    } else {
                        $_pt = new PostTag();
                        $_pt->name = trim($t);
                        $_pt->slug = CommonServiceProvider::convertURL(trim($t));
                        $_pt->status = 1;
                    }
                    $_pt->save();
                }
                $p->status = 1;
                $p->created_at = date('Y-m-d H:i:s');
                $p->save();
            }
        }

        // Twitter
        $posts = Post::inRandomOrder()
            ->where('status', 0)
            ->where('source_type', self::$sourceType['twitter'])
            ->limit($limit)
            ->get();
        if (!$posts->isEmpty()) {
            foreach ($posts as $k => $p) {
                echo $k.' - '.$p->title.PHP_EOL;
                $p->status = 1;
                $p->created_at = date('Y-m-d H:i:s');
                $p->save();
            }
        }
    }
}
