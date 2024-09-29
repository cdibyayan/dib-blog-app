<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class MainController extends Controller
{
    public function index()
    {
        $posts = Cache::remember('posts', 60, function () {
            return Post::with('user')->latest()->get();
        });
        return view('main', compact('posts'));
    }

    public function viewPost($slug)
    {
        $post = Cache::remember("post-{$slug}", 60, function () use ($slug) {
            return Post::where('slug', $slug)
                ->with(['user', 'comments.user']) // Eager load comments and the user who posted the comment
                ->firstOrFail();
        });

        // $post = Post::where('slug', $slug)
        //     ->with(['user', 'comments.user']) // Eager load comments and the user who posted the comment
        //     ->firstOrFail();
        return view('single-blog', compact('post'));
    }

    public function pusher()
    {
        return view('pusher');
    }
}
