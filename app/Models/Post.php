<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public static $zones = [
        'service'   => "Servicii",
        'blog'  => "Noutati legislative"
    ];

    protected $table = "posts";
    protected $fillable = ['title', 'description', 'content', 'slug', 'zone', 'is_active'];

    public function image()
    {
        return $this->hasOne('App\Models\Image');
    }

}