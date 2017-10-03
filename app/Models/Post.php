<?php
/**
 * Created by PhpStorm.
 * User: QueenB
 * Date: 25/09/2017
 * Time: 14:49
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public static $zones = [
        'home' => "Home",
        'service'   => "Servicii",
        'blog'  => "Noutati legislative",
        'contact'   => "Contact"
    ];

    protected $table = "posts";
    protected $fillable = ['title', 'description', 'content', 'slug', 'zone', 'is_active'];

    public function image()
    {
        return $this->hasOne('App\Models\Image');
    }

}