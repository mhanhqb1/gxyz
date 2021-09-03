<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model {
    protected $table = "post_images";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'image',
        'status'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    public $timestamps = true;
}
