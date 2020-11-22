<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterSource;

class YoutubeChannelVideo extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'youtube_channel_id',
        'youtube_id',
        'title',
        'description',
        'published_at',
        'image',
        'total_view',
        'total_comment',
        'total_like',
        'total_dislike',
        'category_id',
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
    
    public static function get_list($params) {
        # Init
        $limit = !empty($params['limit']) ? $params['limit'] : 16;
        $page = !empty($params['page']) ? $params['page'] : 1;
        $offset = ($page - 1)*$limit;

        # Get data
        $data = self::orderBy('id', 'desc');

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

}
