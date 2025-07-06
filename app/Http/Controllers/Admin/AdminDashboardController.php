<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $premiumUsers = User::where('is_subscribed', true)->count();
        $latestArticles = Article::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'userCount',
            'premiumUsers',
            'latestArticles'
        ));
    }
}


