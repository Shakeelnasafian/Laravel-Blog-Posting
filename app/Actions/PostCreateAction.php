<?php

namespace App\Actions;

use App\Models\Post;

/**
 * Handles the creation of a new blog post.
 *
 * This action is responsible for processing the data required to create a new post,
 * including validation, persistence, and any related business logic.
 */
class PostCreateAction
{
    /**
     * Handles the creation of a post with the provided data.
     *
     * @param array $data The data required to create a new post.
     * @return mixed The result of the post creation process.
     */
    public function handle(array $data)
    {
        $data['user_id'] = auth()->id();
        $newPost = Post::create($data);
        return $newPost;
    }
}
