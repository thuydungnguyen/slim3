<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = ['post_id', 'name'];

    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }

}