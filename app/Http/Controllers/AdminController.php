<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\YoutubeChannelVideo;
use App\Models\MasterSource;
use App\Models\Video;
use App\Models\Post;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Get list images
     */
    public static function checkImages(Request $request)
    {
        $params = $request->all();
        $params['limit'] = 999;
        $images = Image::get_list($params);
        return view('admin.image', ['images' => $images, 'params' => $params]);
    }

    /**
     * Get list images
     */
    public static function checkVideos(Request $request)
    {
        $params = $request->all();
        $params['limit'] = 999;
        $videos = Video::get_list($params);
        return view('admin.video', ['videos' => $videos, 'params' => $params]);
    }

    /**
     * Add source
     */
    public static function addSource(Request $request)
    {
        $types = MasterSource::$type;
        $sourceTypes = MasterSource::$sourceType;
        $loops = MasterSource::$loop;
        $params = $request->all();
        $params['limit'] = 999;
        $data = MasterSource::get_list($params);
        return view('admin.add_source', ['params' => $params, 'data' => $data, 'types' => $types, 'sourceTypes' => $sourceTypes, 'loops' => $loops]);
    }

    /**
     * Update image
     */
    public static function saveSource(Request $request)
    {
        $params = $request->all();
        $type = !empty($params['type']) ? $params['type'] : '';
        $sourceType = !empty($params['source_type']) ? $params['source_type'] : '';
        $name = !empty($params['name']) ? $params['name'] : '';
        $sourceParams = !empty($params['source_params']) ? $params['source_params'] : '';
        $loop = !empty($params['loop']) ? $params['loop'] : '';
        $isOwner = !empty($params['is_owner']) ? $params['is_owner'] : 0;
        $customTags = !empty($params['custom_tags']) ? $params['custom_tags'] : '';

        $masterSource = new MasterSource();
        $masterSource->type = $type;
        $masterSource->source_type = $sourceType;
        $masterSource->source_params = $sourceParams;
        $masterSource->name = $name;
        $masterSource->loop = $loop;
        $masterSource->status = 1;
        $masterSource->is_owner = $isOwner;
        $masterSource->custom_tags = $customTags;
        $masterSource->save();

        return redirect('/addSource');
    }

    /**
     * Update image
     */
    public static function ajaxUpdateImages(Request $request)
    {
        # Init
        $result = [
            'status' => 'OK',
            'message' => ''
        ];
        $params = $request->all();
        $ids = !empty($params['ids']) ? explode(',', $params['ids']) : [];
        $field = !empty($params['field']) ? $params['field'] : '';
        $val = !empty($params['val']) ? $params['val'] : 0;

        if (!empty($ids) && !empty($field) && isset($val)) {
            if ($field == 'status' && $val == '-1') {
                Image::whereIn('id', $ids)->delete();
            } else {
                Image::whereIn('id', $ids)->update([ $field => $val ]);
            }
        }

        echo json_encode($result);
        die();
    }

    /**
     * Update image
     */
    public static function ajaxUpdateVideos(Request $request)
    {
        # Init
        $result = [
            'status' => 'OK',
            'message' => ''
        ];
        $params = $request->all();
        $ids = !empty($params['ids']) ? explode(',', $params['ids']) : [];
        $field = !empty($params['field']) ? $params['field'] : '';
        $val = !empty($params['val']) ? $params['val'] : 0;

        if (!empty($ids) && !empty($field) && isset($val)) {
            if ($field == 'status' && $val == '-1') {
                Video::whereIn('id', $ids)->delete();
            } else {
                Video::whereIn('id', $ids)->update([ $field => $val ]);
            }
        }

        echo json_encode($result);
        die();
    }

    /**
     * Update image
     */
    public static function ajaxUpdateSources(Request $request)
    {
        # Init
        $result = [
            'status' => 'OK',
            'message' => ''
        ];
        $params = $request->all();
        $ids = !empty($params['ids']) ? explode(',', $params['ids']) : [];
        $field = !empty($params['field']) ? $params['field'] : '';
        $val = !empty($params['val']) ? $params['val'] : 0;

        if (!empty($ids) && !empty($field) && isset($val)) {
            if ($field == 'status' && $val == '-1') {
                MasterSource::whereIn('id', $ids)->delete();
            } else {
                MasterSource::whereIn('id', $ids)->update([ $field => $val ]);
            }
        }

        echo json_encode($result);
        die();
    }
}
