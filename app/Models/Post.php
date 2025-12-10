<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'image_path', 'released_at', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public static function createPost(array $data)
    {
        $post = new self();
        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->released_at = $data['released_at'];
        if (isset($data['image'])) {
            $imagePath = $data['image']->store('posts', 'public');
            $post->image_path = $imagePath;
        }
        $post->user_id = auth()->id();
        $post->save();
        return $post;
    }
    public static function updatePost(Post $post, array $data)
    {
        $post->title = $data['title'] ?? $post->title;
        $post->content = $data['content'] ?? $post->content;
        $post->released_at = $data['released_at'] ?? $post->released_at;
        if (isset($data['image'])) {
            $imagePath = $data['image']->store('posts', 'public');
            $post->image_path = $imagePath;
        }
        $post->save();
        return $post;
    }
}
