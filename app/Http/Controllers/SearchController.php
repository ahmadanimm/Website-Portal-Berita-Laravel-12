<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        $articles = Article::with('category')
            ->where('title', 'like', "%{$query}%")
            ->latest()
            ->get();

        return view('search', compact('articles', 'query'));
    }


}