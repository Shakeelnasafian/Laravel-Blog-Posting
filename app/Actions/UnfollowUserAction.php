<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

/**
 * Action class responsible for handling the logic to unfollow a user.
 *
 * This class encapsulates the operations required to remove a follow relationship
 * between the authenticated user and another user in the application.
 *
 * Usage:
 *   $action = new UnfollowUserAction();
 *   $action->execute($userToUnfollow);
 */
class UnfollowUserAction
{

    /**
     * Handles the logic for unfollowing a user.
     *
     * @param User $user The user to be unfollowed.
     * @return void
     */
    public function handle(User $user)
    {
        $authUserId = Auth::id();

        Follow::where('user_id', $authUserId)
            ->where('followeduser', $user->id)
            ->delete();
    }
}
