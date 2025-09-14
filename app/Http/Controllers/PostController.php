<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use App\Jobs\SendNewPostEmail;
use App\Actions\PostCreateAction;
use App\Actions\PostUpdateAction;
use App\Http\Requests\PostsRequest;

class PostController extends Controller
{
    public function search($term)
    {
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
        //return Post::where('title', 'LIKE', '%' . $term . '%')->orWhere('body', 'LIKE', '%' . $term . '%')->with('user:id,username,avatar')->get();
    }

    public function update(Post $post, PostsRequest $request, PostUpdateAction $postUpdateAction)
    {
        $postUpdateAction->handle($request->validated(), $post);

        return back()->with('success', 'Post successfully updated.');
    }

    public function edit(Post $post)
    {
        return view('edit-post', ['post' => $post]);
    }

    public function delete(Post $post)
    {
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post successfully deleted.');
    }

    public function store(PostsRequest $request, PostCreateAction $postCreateAction)
    {
        $newPost = $postCreateAction->handle($request->validated());

        dispatch(new SendNewPostEmail([
            'sendTo' => auth()->user()->email,
            'name' => auth()->user()->username,
            'title' => $newPost->title
        ]));

        return redirect("/post/{$newPost->id}")->with('success', 'New post successfully created.');
    }

    public function create()
    {
        return view('create-post');
    }

    public function view(Post $post)
    {
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h3><br>');
        return view('single-post', ['post' => $post]);
    }
}
