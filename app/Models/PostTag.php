<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model {

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
}
