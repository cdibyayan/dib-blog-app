<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        $comment = new Comment;
        $comment->post_id = $postId;
        $comment->user_id = auth()->id(); // Current logged-in user
        $comment->body = $request->comment;
        $comment->save();

        return back()->with('success', 'Comment added successfully.');
    }
}
