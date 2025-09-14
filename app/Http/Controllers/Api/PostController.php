<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Jobs\SendNewPostEmail;
use App\Http\Requests\PostsRequest;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function delete(Post $post)
    {
        $post->delete();
        return 'true';
    }

    public function store(PostsRequest $request)
    {
        $incomingFields = $request->validated();
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost = Post::create($incomingFields);

        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'name' => auth()->user()->username, 'title' => $newPost->title]));

        return $newPost->id;
    }
}
