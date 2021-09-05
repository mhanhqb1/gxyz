<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Providers\CommonServiceProvider;

class PostTag extends Model
{

    protected $table = "post_tags";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'count',
        'status'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    public $timestamps = true;

    // Add update tag
    public static function addUpdateTags($tags, $type)
    {
        $_typeText = !empty($type) ? 'video' : 'image';
        $tags = explode(',', str_replace("{type}", $_typeText, $tags));
        foreach ($tags as $t) {
            $t = trim($t);
            $_pt = PostTag::where('name', $t)->first();
            if (!empty($_pt)) {
                $_pt->count = $_pt->count + 1;
            } else {
                $_pt = new PostTag();
                $_pt->name = $t;
                $_pt->slug = CommonServiceProvider::convertURL($t);
                $_pt->status = 1;
            }
            $_pt->save();
        }
    }
}
