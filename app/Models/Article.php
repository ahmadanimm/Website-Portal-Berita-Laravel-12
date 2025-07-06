<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'slug', 'body', 'thumbnail',
        'category_id', 'author_id', 'is_premium'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function usersSaved()
    {
        return $this->belongsToMany(User::class, 'article_user_saved')->withTimestamps();
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'article_user_likes')->withTimestamps();
    }

    public function dislikedByUsers()
    {
        return $this->belongsToMany(User::class, 'article_user_dislike')->withTimestamps();
    }

    public function bookmarkedByUsers()
    {
        return $this->belongsToMany(User::class, 'article_user_saved');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }
    
}
