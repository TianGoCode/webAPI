<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function in(){
        return $this->belongsTo('App\Models\Post','on_post');
    }

    public function by(){
        return $this->belongsTo('App\Models\User','from_user');
    }
}
