<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Post;
use App\Models\PostTag;

class SitemapXmlController extends Controller
{
    public function index(Request $request) {
        $params = $request->all();
        $page = !empty($params['page']) ? $params['page'] : 1;
        $limit = !empty($params['limit']) ? $params['limit'] : 1000;
        $isTag = !empty($params['is_tag']) ? 1 : 0;
        $offset = ($page - 1)*$limit;
        $posts = [];
        $tags = [];
        if (!empty($isTag)) {
            $tags = PostTag::where('status', 1)
                ->where('name', '!=', '')
                ->whereNotNull('created_at')
                ->limit($limit)
                ->offset($offset)
                ->orderBy('id', 'asc')
                ->get();
        } else {
            $posts = Post::where('status', 1)
                ->whereNotNull('created_at')
                ->orderBy('id', 'asc')
                ->limit($limit)
                ->offset($offset)
                ->get();
        }

        return response()->view('home.sitemap', [
            'posts' => $posts,
            'params' => $params,
            'page' => $page,
            'isTag' => $isTag,
            'tags' => $tags,
            'dateNow' => Carbon::now()
        ])->header('Content-Type', 'text/xml');
    }

}
