<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PostController;


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('auth/{provider}', [SocialController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [SocialController::class, 'handleProviderCallback']);
// Route::resource('posts', PostController::class)->middleware('auth');
//Route::get('posts', [PostController::class, 'index'])->name('posts.index')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/view-posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/create-posts', [PostController::class, 'create'])->name('posts.create');
    Route::post('/post-store', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');
    Route::put('/posts/edit/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/edit/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::group(['middleware' => 'admin'], function () {
        Route::get('/admin', [AdminController::class, 'index']);

        Route::get('/view-all-posts', [AdminController::class, 'index'])->name('posts.all');
        Route::get('/all-posts/{slug}', [AdminController::class, 'show'])->name('posts.showAll');
        Route::put('/all-posts/edit/{post}', [AdminController::class, 'update'])->name('posts.updateAll');
        Route::delete('/all-posts/edit/{post}', [AdminController::class, 'destroy'])->name('posts.destroyAll');
    });
});

Route::get('/', [MainController::class, 'index']);
Route::get('/view-post/{slug}', [MainController::class, 'viewPost'])->name('posts.view');
Route::get('/pusher', [MainController::class, 'pusher'])->name('pusher');
