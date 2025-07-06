<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Article;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $categories = Category::all(); 
        $category = Category::where('slug', $slug)->firstOrFail();

        $articles = Article::where('category_id', $category->id)
                        ->latest()
                        ->paginate(12);

        return view('public.category', compact('category', 'articles', 'categories'));
    }

}
