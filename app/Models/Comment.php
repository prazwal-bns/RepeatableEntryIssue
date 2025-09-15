<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['post_id', 'content', 'author_name'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
