<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AuthorController as PublicAuthorController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController; 
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AuthorController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return redirect('/');
    })->name('dashboard');

    Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');

    Route::get('/berlangganan', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscribe/{type}', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::post('/unsubscribe', [SubscriptionController::class, 'unsubscribe'])->name('subscription.unsubscribe');
    Route::post('/subscription/trial', [SubscriptionController::class, 'startTrial'])->name('subscription.trial');

});


Route::middleware(['auth', 'verified', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('articles', ArticleController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('authors', AuthorController::class);
    Route::delete('authors/delete-selected', [AuthorController::class, 'destroy'])->name('admin.authors.destroy');
    Route::delete('/authors/bulk-delete', [AuthorController::class, 'bulkDelete'])->name('authors.bulkDelete');
    Route::delete('categories/bulk-delete', [\App\Http\Controllers\Admin\CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
    Route::delete('/articles/bulk-delete', [ArticleController::class, 'bulkDelete'])->name('articles.bulkDelete');
    Route::get('stats', [StatsController::class, 'index'])->name('stats');
});


Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/article/{slug}', [ArticleController::class, 'show'])->name('article.show');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/cari', [SearchController::class, 'index'])->name('search');
Route::get('/authors/{author}', [PublicAuthorController::class, 'show'])->name('author.show');
Route::post('/articles/{article}/like', [ArticleController::class, 'like'])->name('articles.like')->middleware('auth');
Route::post('/articles/{article}/dislike', [ArticleController::class, 'dislike'])->name('articles.dislike');
Route::post('/articles/{article}/bookmark', [ArticleController::class, 'bookmark'])->name('articles.bookmark');
Route::post('/articles/{id}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/articles/{article}/rate', [RatingController::class, 'store'])->name('ratings.store');




require __DIR__.'/auth.php';
