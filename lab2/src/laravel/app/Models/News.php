<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    public $timestamps = false;

    protected $fillable = ['title', 'body', 'author', 'created_at', 'likes_count'];

    protected $casts = [
        'created_at'  => 'datetime',
        'likes_count' => 'integer',
    ];
}
