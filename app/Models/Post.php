<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'thumbnail', 'user_id'];

    // Post belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Post has many Comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->slug = Post::createUniqueSlug($post->title);
        });

        static::updating(function ($post) {
            $post->slug = Post::createUniqueSlug($post->title, $post->id);
        });
    }

    public static function createUniqueSlug($title, $postId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;

        $count = 1;

        // Exclude soft-deleted posts when checking for existing slugs
        while (static::withTrashed()->where('slug', $slug)->where('id', '!=', $postId)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
