<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Subscription;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $user->load(['subscriptions' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $savedArticles = $user->savedArticles()->with('category')->latest()->get();
        $likedArticles = $user->likedArticles()->with('category')->latest()->get();

        return view('public.profile', compact('savedArticles', 'user', 'likedArticles'));
    }

    public function edit()
    {
        return view('public.profile-edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        $user->name = $request->name;

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = basename($path);
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }


    public function destroy(Request $request)
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Akun Anda telah dihapus.');
    }
}
