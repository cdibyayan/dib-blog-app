<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['body', 'post_id', 'user_id'];

    // Comment belongs to a Post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Comment belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
