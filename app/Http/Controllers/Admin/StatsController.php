<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $premiumUsers = User::where('is_subscribed', true)
            ->where('subscribed_until', '>', now())
            ->count();

        $totalArticles = Article::count();
        $premiumArticles = Article::where('is_premium', true)->count();

        return view('admin.stats', compact(
            'totalUsers',
            'premiumUsers',
            'totalArticles',
            'premiumArticles'
        ));
    }
}
