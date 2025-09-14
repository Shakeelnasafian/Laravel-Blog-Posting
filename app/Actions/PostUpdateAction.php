<?php

namespace App\Actions;

use App\Models\Post;

class PostUpdateAction
{
    public function handle(array $data, Post $post): object
    {
        $post->update($data);
        return $post;
    }
}
