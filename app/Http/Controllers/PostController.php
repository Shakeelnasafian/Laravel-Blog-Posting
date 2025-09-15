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

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('create-post');
    }

    /**
     * Display the specified post.
     *
     * @param  \App\Models\Post  $post  The post instance to display.
     * @return \Illuminate\Http\Response
     */
    public function view(Post $post)
    {
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h3><br>');
        return view('single-post', ['post' => $post]);
    }

    /**
     * Update the specified post in storage.
     *
     * @param  \App\Models\Post  $post  The post instance to update.
     * @param  \App\Http\Requests\PostsRequest  $request  The validated request containing update data.
     * @param  \App\Actions\PostUpdateAction  $postUpdateAction  The action class responsible for updating the post.
     * @return \Illuminate\Http\Response
     */
    public function update(Post $post, PostsRequest $request, PostUpdateAction $postUpdateAction)
    {
        $postUpdateAction->handle($request->validated(), $post);

        return back()->with('success', 'Post successfully updated.');
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param  \App\Models\Post  $post  The post instance to edit.
     * @return \Illuminate\View\View
     */
    public function edit(Post $post)
    {
        return view('edit-post', ['post' => $post]);
    }

    /**
     * Delete the specified post from storage.
     *
     * @param  \App\Models\Post  $post  The post instance to be deleted.
     * @return \Illuminate\Http\Response
     */
    public function delete(Post $post)
    {
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post successfully deleted.');
    }

    /**
     * Store a newly created post in storage.
     *
     * @param  \App\Http\Requests\PostsRequest  $request  The validated request instance containing post data.
     * @param  \App\Actions\PostCreateAction  $postCreateAction  The action class responsible for creating a post.
     * @return \Illuminate\Http\Response
     */
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

    
}
