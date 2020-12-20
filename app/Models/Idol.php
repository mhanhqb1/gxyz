<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterSource;

class Idol extends Model {
    protected $table = "models";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'source_url',
        'crawl_at'
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
        if (!empty($params['is_random'])) {
            $data = self::inRandomOrder();
        } else {
            $data = self::orderBy('id', 'desc');
        }

        # Return data
        $data = $data->offset($offset)->limit($limit)->get();
        return $data;
    }

}
