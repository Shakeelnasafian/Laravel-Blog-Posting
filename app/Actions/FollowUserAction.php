<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Class FollowUserAction
 *
 * Handles the logic for following a user within the application.
 *
 * This action is responsible for managing the process of one user following another,
 * including any necessary validation, database updates, and event dispatching.
 *
 * Usage:
 *   $action = new FollowUserAction();
 *   $action->execute($follower, $followee);
 *
 * @package App\Actions
 */
class FollowUserAction
{
    /**
     * Handles the action of following a user.
     *
     * @param User $user The user to be followed.
     * @return mixed The result of the follow action.
     */
    public function handle(User $user)
    {
        $authUserId = Auth::id();

        if ($user->id === $authUserId) {
            throw ValidationException::withMessages([
                'user' => 'You cannot follow yourself.',
            ]);
        }

        $exists = Follow::where('user_id', $authUserId)
            ->where('followeduser', $user->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'user' => 'You are already following this user.',
            ]);
        }

        Follow::create([
            'user_id' => $authUserId,
            'followeduser' => $user->id,
        ]);
    }
}
