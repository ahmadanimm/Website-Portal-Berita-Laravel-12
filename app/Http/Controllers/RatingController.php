<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, $articleId)
    {
        if (!Auth::check()) {
            return back()->withErrors('Anda harus login untuk memberi ulasan.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $userId = Auth::id();

        $article = Article::findOrFail($articleId);

        $existing = Rating::where('user_id', $userId)
                          ->where('article_id', $articleId)
                          ->first();

        if ($existing) {
            $existing->update(['rating' => $request->rating]);
            $message = 'Ulasan Anda berhasil diperbarui!';
        } else {
            Rating::create([
                'user_id'    => $userId,
                'article_id' => $articleId,
                'rating'     => $request->rating,
            ]);
            $message = 'Terima kasih atas ulasan Anda!';
        }

        if ($article->author) { 
            $article->author->updateAuthorRating(); 
        } else {
            // Opsional: Log jika artikel tidak memiliki penulis yang terkait
            // Log::warning("Artikel ID {$article->id} tidak memiliki penulis yang terkait untuk update rating.");
        }

        return redirect()->back()->with([
            'success'     => $message,
            'rated_value' => $request->rating
        ]);
    }
}