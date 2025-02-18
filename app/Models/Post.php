<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'image', 'author_id'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
