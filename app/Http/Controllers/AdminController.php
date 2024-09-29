<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        Cache::forget('posts');
        $posts = Cache::remember('posts', 10, function () {
            return Post::with('user')
                ->latest()->get();
        });
        //print_r($posts);
        return view('posts.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Cache::remember("post-{$slug}", 60, function () use ($slug) {
            return Post::where('slug', $slug)->firstOrFail();
        });
        //$post = Post::where('slug', $slug)->firstOrFail();
        return view('posts.show', compact('post'));
    }

    // Edit a post
    public function edit(Post $post)
    {
        $this->authorize('update', $post); // Ensure the user owns the post
        return view('posts.edit', compact('post'));
    }

    // Update the post
    public function update(Request $request, Post $post)
    {
        //$this->authorize('update', $post);
        if ($request->hasFile('thumbnail')) {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'content' => 'required',
                'thumbnail' => 'image|mimes:jpeg,png,jpg,gif',
            ]);

            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $post->thumbnail = $thumbnailPath;
        } else {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'content' => 'required',
            ]);
        }

        $post->update($validated);
        Cache::forget("post-{$post->slug}");
        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    // Delete a post
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
