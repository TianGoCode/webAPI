<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function postedBy(){
        return $this->belongsTo('App\Models\User',"author_id");
    }

    public function hasCmts(){
        return $this->hasMany('App\Models\Comment', 'on_post');
    }
}
