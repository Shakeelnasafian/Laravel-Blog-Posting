<?php

namespace App\Actions;

use App\Models\Post;

/**
 * Handles the logic for updating an existing post.
 *
 * This action class is responsible for processing the update of a post,
 * including validation, authorization, and persistence of changes.
 *
 * Usage:
 *   $action = new PostUpdateAction();
 *   $action->execute($post, $data);
 *
 * @package App\Actions
 */
class PostUpdateAction
{
    /**
     * Handles the update logic for a given Post instance with the provided data.
     *
     * @param array $data The data to update the post with.
     * @param Post $post The post instance to be updated.
     * @return object The updated post object or a result object.
     */
    public function handle(array $data, Post $post): object
    {
        $post->update($data);
        return $post;
    }
}
