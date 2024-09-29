<?php

namespace App\Http\Controllers;

use App\Events\NewPostAdded;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    // Display all posts
    public function index()
    {
        Cache::forget('posts');
        $posts = Cache::remember('posts', 10, function () {
            $user = Auth::user();
            $u_id = $user->id;
            return Post::with('user')
                ->where('user_id', $u_id)
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

    // Show form for creating a new post
    public function create()
    {
        return view('posts.create');
    }

    // Store new post
    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $user_name = $user->name;
        //dd($request);
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif', // Thumbnail validation
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'thumbnail' => $thumbnailPath,
            'user_id' => $user_id,
        ]);

        $post = [
            'title' => $validated['title'],
            'name' => $user_name,
        ];

        //Event Call for Notification
        event(new NewPostAdded($post));

        // Clear the cache
        Cache::forget('posts');

        return redirect()->route('posts.create')->with('success', 'Post created successfully.');
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
