<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('category');

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $articles = $query->latest()->paginate(10); 

        return view('admin.articles.index', compact('articles'));
    }


    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.articles.create', compact('categories'));
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)
            ->with(['author', 'category']) 
            ->firstOrFail();
        
        if ($article->is_premium) {
            $user = auth()->user();

            if (!$user || !$user->is_subscribed) {
                return redirect()->route('profile')->with('error', 'Untuk mengakses artikel premium, Anda harus berlangganan terlebih dahulu.');
            }
        }

        $moreFromAuthor = \App\Models\Article::with('category')
            ->where('author_id', $article->author_id)
            ->where('id', '!=', $article->id)
            ->latest()
            ->take(5)
            ->get();

        return view('public.article', compact('article', 'moreFromAuthor'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'thumbnail' => 'nullable|image|max:2048',
            'body' => 'required|string',
        ]);

        $validated['is_premium'] = $request->has('is_premium');
        $validated['author_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article)
    {
        $categories = Category::all();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'body' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($article->thumbnail) {
                \Storage::disk('public')->delete($article->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['slug'] = \Str::slug($validated['title']);
        $validated['is_premium'] = $request->has('is_premium');

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diupdate!');
    }


    public function destroy(Request $request, $id)
    {
        if ($request->has('selected_ids')) {
            $ids = explode(',', $request->input('selected_ids'));

            $articles = \App\Models\Article::whereIn('id', $ids)->get();
            foreach ($articles as $article) {
                if ($article->thumbnail) {
                    \Storage::disk('public')->delete($article->thumbnail);
                }
            }

            \App\Models\Article::whereIn('id', $ids)->delete();
            return redirect()->route('admin.articles.index')->with('success', 'Artikel yang dipilih berhasil dihapus.');
        }

        $article = \App\Models\Article::findOrFail($id);
        if ($article->thumbnail) {
            \Storage::disk('public')->delete($article->thumbnail);
        }
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }


    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('selected_ids'));

        $articles = \App\Models\Article::whereIn('id', $ids)->get();
        foreach ($articles as $article) {
            if ($article->thumbnail) {
                \Storage::disk('public')->delete($article->thumbnail);
            }
        }

        \App\Models\Article::whereIn('id', $ids)->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel yang dipilih berhasil dihapus.');
    }

    public function like(Article $article)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        $article->dislikedByUsers()->detach($user->id);

        if ($article->likedByUsers()->where('user_id', $user->id)->exists()) {
            $article->likedByUsers()->detach($user->id);
        } else {
            $article->likedByUsers()->attach($user->id);
        }

        return back();
    }

    public function dislike(Article $article)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        $article->likedByUsers()->detach($user->id);

        if ($article->dislikedByUsers()->where('user_id', $user->id)->exists()) {
            $article->dislikedByUsers()->detach($user->id);
        } else {
            $article->dislikedByUsers()->attach($user->id);
        }

        return back();
    }

    public function bookmark(Article $article)
    {
        $user = auth()->user();

        if ($user->bookmarkedArticles->contains($article->id)) {
            $user->bookmarkedArticles()->detach($article->id);
        } else {
            $user->bookmarkedArticles()->attach($article->id);
        }

        return back();
    }


}
