<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{

    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_subscribed',      
        'subscription_type',  
        'subscription_start', 
        'subscription_end',   
        'trial_used',         
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'subscribed_until' => 'datetime',
        'is_subscribed' => 'boolean', 
        'trial_used' => 'boolean',    
        'subscription_start' => 'datetime', 
        'subscription_end' => 'datetime',   
    ];

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function savedArticles()
    {
        return $this->belongsToMany(Article::class, 'article_user_saved')->withTimestamps();
    }

    public function likedArticles()
    {
        return $this->belongsToMany(Article::class, 'article_user_likes')->withTimestamps();
    }

    public function dislikedArticles()
    {
        return $this->belongsToMany(Article::class, 'article_user_dislike')->withTimestamps();
    }

    public function bookmarkedArticles()
    {
        return $this->belongsToMany(Article::class, 'article_user_saved');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function receivedRatings()
    {
        
        return $this->hasManyThrough(
            Rating::class, 
            Article::class, 
            'user_id',      
            'article_id',   
            'id',           
            'id'            
        );
    }

    
    public function updateAuthorRating()
    {
        
        $ratingData = DB::table('ratings')
            ->join('articles', 'ratings.article_id', '=', 'articles.id')
            ->where('articles.user_id', $this->id) 
            ->select(DB::raw('SUM(ratings.rating) as total_rating'), DB::raw('COUNT(ratings.rating) as rating_count'))
            ->first();

        $totalRatingSum = $ratingData->total_rating ?? 0;
        $totalRatingCount = $ratingData->rating_count ?? 0;

        $this->ratings_count = $totalRatingCount;
        $this->average_rating = ($totalRatingCount > 0) ? round($totalRatingSum / $totalRatingCount, 1) : 0.0;
        $this->save();
    }

}