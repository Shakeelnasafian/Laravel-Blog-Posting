<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Actions\FollowUserAction;
use App\Actions\UnfollowUserAction;
use Illuminate\Validation\ValidationException;

class FollowController extends Controller
{
    /**
     * Handles the creation of a follow relationship for the given user.
     *
     * @param  \App\Models\User  $user  The user to be followed.
     * @param  \App\Actions\FollowUserAction  $followUserAction  The action class responsible for following a user.
     * @return \Illuminate\Http\Response
     */
    public function createFollow(User $user, FollowUserAction $followUserAction)
    {
        try {
            $followUserAction->handle($user);
            return back()->with('success', 'User successfully followed.');
        } catch (ValidationException $e) {
            return back()->with('failure', $e->getMessage());
        }
    }

    /**
     * Remove the follow relationship for the given user.
     *
     * @param  \App\Models\User  $user  The user to unfollow.
     * @param  \App\Actions\UnfollowUserAction  $unfollowUserAction  The action class to handle unfollowing logic.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFollow(User $user, UnfollowUserAction $unfollowUserAction)
    {
        try {
            $unfollowUserAction->handle($user);
            return back()->with('success', 'User successfully unfollowed.');
        } catch (ValidationException $e) {
            return back()->with('failure', $e->getMessage());
        }
    }
}
