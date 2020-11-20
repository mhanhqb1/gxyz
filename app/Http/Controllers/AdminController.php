<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;

use Illuminate\Http\Request;

class AdminController extends Controller
{   
    /**
     * Get list images
     */
    public static function checkImages(Request $request)
    {
        $params = $request->all();
        $images = Image::get_list($params);
        return view('admin.image', ['images' => $images, 'params' => $params]);
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
}