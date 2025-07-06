<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'avatar', 'average_rating', 'ratings_count'];

    protected $casts = [
        'average_rating' => 'float', 
    ];

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id'); 
    }

    public function receivedRatings()
    {
        return $this->hasManyThrough(
            Rating::class,    
            Article::class,   
            'author_id',      
            'article_id',     
            'id',             
            'id'              
        );
    }

    public function updateAuthorRating(): void
    {
        $totalRatingSum = $this->receivedRatings()->sum('rating');
        $totalRatingCount = $this->receivedRatings()->count();

        $calculatedAverage = ($totalRatingCount > 0) ? round($totalRatingSum / $totalRatingCount, 1) : 0.0;

        $this->ratings_count = $totalRatingCount;
        $this->average_rating = $calculatedAverage;
        $this->save();
    }
}
