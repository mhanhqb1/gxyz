<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\YoutubeChannelVideo;
use App\Models\MasterSource;

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
        $videos = YoutubeChannelVideo::get_list($params);
        return view('admin.video', ['videos' => $videos, 'params' => $params]);
    }
    
    /**
     * Add source
     */
    public static function addSource()
    {
        $types = MasterSource::$type;
        $sourceTypes = MasterSource::$sourceType;
        return view('admin.add_source', ['types' => $types, 'sourceTypes' => $sourceTypes]);
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
        
        $masterSource = new MasterSource();
        $masterSource->type = $type;
        $masterSource->source_type = $sourceType;
        $masterSource->source_params = $sourceParams;
        $masterSource->name = $name;
        $masterSource->save();
        
        $types = MasterSource::$type;
        $sourceTypes = MasterSource::$sourceType;
        return view('admin.add_source', ['types' => $types, 'sourceTypes' => $sourceTypes]);
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
        $val = !empty($params['val']) ? $params['val'] : '';
        
        if (!empty($ids) && !empty($field) && !empty($val)) {
            foreach ($ids as $id) {
                $image = Image::find($id);
                if (!empty($image)) {
                    $image->$field = $val;
                    if ($field != 'status' && $val == 1) {
                        $image->status = 1;
                    }
                    $image->save();
                }
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
        $val = !empty($params['val']) ? $params['val'] : '';
        
        if (!empty($ids) && !empty($field) && !empty($val)) {
            foreach ($ids as $id) {
                $image = YoutubeChannelVideo::find($id);
                if (!empty($image)) {
                    $image->$field = $val;
                    if ($field != 'status' && $val == 1) {
                        $image->status = 1;
                    }
                    $image->save();
                }
            }
        }
        
        echo json_encode($result);
        die();
    }
}