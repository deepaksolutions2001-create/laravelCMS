<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $fillable = ['title', 'user_id', 'html', 'css', 'slug', 'is_published', 'meta_description', 'category', 'meta_title'];

    //
}
