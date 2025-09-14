<?php

namespace App\Actions;

use App\Models\Post;

class PostCreateAction
{
    public function handle(array $data)
    {
        $data['user_id'] = auth()->id();
        $newPost = Post::create($data);
        return $newPost;
    }
}
