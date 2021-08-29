<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSource extends Model {

    protected $table = "master_sources";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'source_type',
        'source_params',
        'crawl_at',
        'status',
        'loop',
        'is_owner'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    public $timestamps = true;
    public static $type = [
        'image' => 'image',
        'video' => 'video',
        'movie' => 'movie'
    ];
    public static $loop = [
        'daily' => 'daily',
        'weekly' => 'weekly',
        'monthly' => 'monthly'
    ];
    public static $sourceType = [
        'flickr' => 'flickr',
        'instagram' => 'instagram',
        'youtube' => 'youtube',
        'facebook' => 'facebook',
        'youtube_playlist' => 'youtube_playlist',
        'youtube_video' => 'youtube_video',
        'youtube_key' => 'youtube_key',
        'twitter' => 'twitter'
    ];

    public static function get_list($params){
        # Init
        $limit = !empty($params['limit']) ? $params['limit'] : 1;
        $page = !empty($params['page']) ? $params['page'] : 1;
        $offset = ($page - 1)*$limit;

        # Get data
        $data = self::orderBy('id', 'desc');

        # Filter
        if (!empty($params['type'])) {
            $data = $data->where('type', $params['type']);
        }
        if (!empty($params['source_type'])) {
            $data = $data->where('source_type', $params['source_type']);
        }
        if (!empty($params['loop'])) {
            $data = $data->where('loop', $params['loop']);
        }
        if (isset($params['status'])) {
            $data = $data->where('status', $params['status']);
        }

        # Return data
        $data = $data->offset($offset)->limit($limit)->get();
        return $data;
    }

}
