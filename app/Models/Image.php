<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'cate_id',
        'location_id',
        'person_id',
        'total_view',
        'is_hot',
        'is_18',
        'status',
        'source_id'
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
    
    public static function flickr_firt_crawler() {
        for ($i = 1; $i < 5000; $i++) {
            $data = self::flickr_crawler([
                'page' => $i,
//                'user_id' => '27453474@N02',
                'group_id' => '2707037@N25',
                'sort' => 'date-posted-asc',
                'is_first' => 0
            ]);
            if (empty($data)) {
                break;
            }
        }
    }

    public static function flickr_crawler($params) {
        # Init
        $data = [];
        $page = !empty($params['page']) ? $params['page'] : 1;
        $sort = !empty($params['sort']) ? $params['sort'] : 'date-posted-desc';
        $userId = !empty($params['user_id']) ? $params['user_id'] : '';
        $groupId = !empty($params['group_id']) ? $params['group_id'] : '';
        $apiKey = config('app.flickr_key');
        $apiUrl = 'https://api.flickr.com/services/rest/';
        $apiParams = array(
            'api_key' => $apiKey,
            'method' => 'flickr.photos.search',
            'user_id' => $userId, //'27453474@N02',
            'group_id' => $groupId,
            'format' => 'php_serial',
            'page' => $page,
            'sort' => $sort
        );

        $encoded_params = array();

        foreach ($apiParams as $k => $v) {
            $encoded_params[] = urlencode($k) . '=' . urlencode($v);
        }

        $url = $apiUrl . "?" . implode('&', $encoded_params);

        $rsp = file_get_contents($url);

        $rspObj = unserialize($rsp);
        if ($rspObj['stat'] == 'ok'){
            $page = $rspObj['photos']['page'];
            $totalPage = $rspObj['photos']['pages'];
            if (!empty($rspObj['photos']['photo'])) {
                foreach ($rspObj['photos']['photo'] as $p) {
                    $data[] = [
                        'source_id' => $p['id'],
                        'url' => "https://live.staticflickr.com/{$p['server']}/{$p['id']}_{$p['secret']}.jpg"
                    ];
                }
                if (!empty($params['is_first'])) {
                    self::insert($data);
                } else {
                    foreach ($data as $v) {
                        self::updateOrCreate([
                                'source_id' => $v['source_id']
                            ], [
                            'source_id' => $v['source_id'],
                            'url' => $v['url']
                        ]);
                    }
                }
            }
        }
        return $data;
    }

}
